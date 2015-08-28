<?php

namespace Controller;

use Model\Project as ProjectModel;

/**
 * Task controller
 * ikan
 * @package  controller
 * @author   Hussam lawen
 */
class Spaces extends Base
{
    public function index(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();
        $spaces = $this->project->getSpaces($project['id']);
        $this->response->html($this->projectLayout('project/spaces', array(
            'values' => $values + array('project_id' => $project['id']),
            'errors' => $errors,
            'spaces' => $spaces,
            'project' => $project,
            'title' => t('Spaces')
        )));
    }
}
