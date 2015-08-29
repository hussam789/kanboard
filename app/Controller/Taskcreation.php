<?php

namespace Controller;

/**
 * Task Creation controller
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class Taskcreation extends Base
{
    // ikan
    const ALL_SPACES = 'All Spaces';
    /**
     * Display a form to create a new task
     *
     * @access public
     */
    public function create(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();
        $method = $this->request->isAjax() ? 'render' : 'layout';
        $swimlanes_list = $this->swimlane->getList($project['id'], false, true);

        if (empty($values)) {

            $values = array(
                'swimlane_id' => $this->request->getIntegerParam('swimlane_id', key($swimlanes_list)),
                'column_id' => $this->request->getIntegerParam('column_id'),
                'color_id' => $this->request->getStringParam('color_id', $this->color->getDefaultColor()),
                'owner_id' => $this->request->getIntegerParam('owner_id'),
                'another_task' => $this->request->getIntegerParam('another_task'),
            );
        }
        //ikan
        $new_array = array();
        $this->taskFinder->recursive(json_decode($this->project->getSpaces($project['id']), true), 0, $new_array, "");
        $new_array[self::ALL_SPACES] = self::ALL_SPACES;
        $category = array($values['swimlane_id'] => $this->category->getNameById($values['swimlane_id']));

        $this->response->html($this->template->$method('task_creation/form', array(
            'ajax' => $this->request->isAjax(),
            'errors' => $errors,
            'values' => $values + array('project_id' => $project['id']),
            'columns_list' => $this->board->getColumnsList($project['id']),
            'users_list' => $this->projectPermission->getMemberList($project['id'], true, false, true),
            'colors_list' => $this->color->getList(),
            // ikan
            'spaces_list' => array_combine($new_array, $new_array),
            // ikan
            'categories_list' => $category,
            'swimlanes_list' => $swimlanes_list,
            'date_format' => $this->config->get('application_date_format'),
            'date_formats' => $this->dateParser->getAvailableFormats(),
            'title' => $project['name'].' &gt; '.t('New task')
        )));
    }

    /**
     * Validate and save a new task
     *
     * @access public
     */
    public function save()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->taskValidator->validateCreation($values);

        if ($valid) {
            // ikan
            foreach ($values['spaces'] as $selectedOption) {
                error_log($selectedOption);
            }
            error_log(implode($values));
            if ($values['spaces'] == self::ALL_SPACES) {

                $space_list = array();
                $this->taskFinder->recursive(json_decode($this->project->getSpaces($project['id']), true), 0, $space_list, "");
                if (count($space_list) == 0) {
                    $this->createSingleTask($values, $project);
                } else {
                    $output = print_r($space_list,1);
                    error_log($output);
                    foreach ($space_list as $space_name) {
                        $values['spaces'] = $space_name;
                        $temp_title = $values['title'];
                        $values['title'] .= ' - ' . str_replace('_', '', $space_name);
                        $this->createSingleTask($values, $project);
                        $values['title'] = $temp_title;
                    }
                }
            } else
                $this->createSingleTask($values, $project);
            if (isset($values['another_task']) && $values['another_task'] == 1) {
                unset($values['title']);
                unset($values['description']);
                $this->response->redirect($this->helper->url->to('taskcreation', 'create', $values));
            }
            else {
                $this->response->redirect($this->helper->url->to('board', 'show', array('project_id' => $project['id'])));
            }
            /*if ($this->taskCreation->create($values)) {
                $this->session->flash(t('Task created successfully.'));

                if (isset($values['another_task']) && $values['another_task'] == 1) {
                    unset($values['title']);
                    unset($values['description']);
                    $this->response->redirect($this->helper->url->to('taskcreation', 'create', $values));
                }
                else {
                    $this->response->redirect($this->helper->url->to('board', 'show', array('project_id' => $project['id'])));
                }
            }
            else {
                $this->session->flashError(t('Unable to create your task.'));
            }*/
        }

        $this->create($values, $errors);
    }

    /**
     * @param $values
     * @param $project
     * @return mixed
     * ikan
     */
    private function createSingleTask($values, $project)
    {
        $materialTaskId = 0;
        if (!empty($values['materials'])) {
            error_log('MATERIALS' + $values['materials']);
            // Material Category
            global $materialCatId;
            $materialCatId = $this->category->getIdByName($values['project_id'], 'Materials');
            if ($materialCatId == 0 ) {
                // if not exist create Material Category
                $materialCatId = (int) $this->category->create(array(
                    'project_id' => $values['project_id'],
                    'name' => 'Materials',
                ));
            }
            // Create Material Task
            $materialTaskValues = array(
                'title' => 'Materials - ' . $values['title'],
                'project_id' => $values['project_id'],
                'color_id' => '',
                'column_id' => 0,
                'owner_id' => 0,
                'creator_id' => $values['creator_id'],
                'date_due' => '',
                'description' => '',
                'category_id' => $materialCatId,
                'spaces' => $values['spaces'],
                'score' => 0,
                'swimlane_id' => $values['swimlane_id'],
            );
            $materialTaskId = $this->taskCreation->create($materialTaskValues);
            // Create Materials subtasks
            $materials = explode(',', $values['materials']);
            foreach ($materials as $key => $val) {

                $subTask = array(
                    'title' => trim($val),
                    'task_id' => $materialTaskId,
                    'user_id' => 0,
                    'time_estimated' => 0,
                    'time_spent' => 0,
                    'status' => 0,
                );
                $this->subtask->create($subTask);
            }
        }
        unset($values['materials']);
        $originalTask = $this->taskCreation->create($values);
        if ($originalTask) {
            // link material task with original task
            if ($materialTaskId != 0) {
                $this->taskLink->create($materialTaskId, $originalTask, 9);
            }
            $this->session->flash(t('Task created successfully.'));
            return true;
        } else {
            $this->session->flashError(t('Unable to create your task.'));
            return false;
        }
    }
}
