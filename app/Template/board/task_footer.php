<?php /*if (! empty($task['category_id'])): */?>
<div class="task-board-category-container">
    <!-- ikan -->
     <span class="task-board-category">
        <?php if (! empty($task['category_id'])): ?>
            <?php if ($not_editable): ?>
                <?= $this->e($task['category_name']) ?>
            <?php else: ?>
                <?= $this->url->link(
                    $this->e($task['category_name']),
                    'board',
                    'changeCategory',
                    array('task_id' => $task['id'], 'project_id' => $task['project_id']),
                    false,
                    'popover' . (! empty($task['category_description']) ? ' tooltip' : ''),
                    ! empty($task['category_description']) ? $this->text->markdown($task['category_description']) : t('Change category')
                ) ?>
            <?php endif ?>
        <?php endif ?>
     </span>
    <!-- ikan -->
    <span class="task-board-category">
        <?php if (! empty($task['spaces'])): ?>
            <?php if ($not_editable): ?>
                <?= $this->e(str_replace('_', '', $task['spaces'])) ?>
            <?php else: ?>
                <?= $this->url->link(
                    str_replace('_', '', $task['spaces']),
                    'board',
                    'changeSpace',
                    array('task_id' => $task['id'], 'project_id' => $task['project_id'], 'space' => $task['spaces']),
                    false,
                    'popover' . (! empty($task['category_description']) ? ' tooltip' : ''),
                    ! empty($task['category_description']) ? $this->text->markdown($task['category_description']) : t('Change space')
                ) ?>
            <?php endif ?>
        <?php endif ?>
    </span>
    <?php /*if ($task['spaces']): */?><!--
        <span class="task-board-category">
            <?/*= $this->a(
                str_replace('_', '', $task['spaces']),
                'board',
                'changeSpace',
                array('task_id' => $task['id'], 'project_id' => $task['project_id'], 'space' => $task['spaces']),
                false,
                'task-board-popover',
                t('Change space')
            ) */?>
        </span>
    --><?php /*endif */?>
</div>
<?php /*endif */?>

<div class="task-board-icons">
    <?php if (! empty($task['date_due'])): ?>
        <span class="task-board-date <?= time() > $task['date_due'] ? 'task-board-date-overdue' : '' ?>">
            <i class="fa fa-calendar"></i>
            <?= (date('Y') === date('Y', $task['date_due']) ? dt('%b %e', $task['date_due']) : dt('%b %e %Y', $task['date_due'])) ?>
        </span>
    <?php endif ?>

    <?php if ($task['recurrence_status'] == \Model\Task::RECURRING_STATUS_PENDING): ?>
        <span title="<?= t('Recurrence') ?>" class="tooltip" data-href="<?= $this->url->href('board', 'recurrence', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>"><i class="fa fa-refresh fa-rotate-90"></i></span>
    <?php endif ?>

    <?php if ($task['recurrence_status'] == \Model\Task::RECURRING_STATUS_PROCESSED): ?>
        <span title="<?= t('Recurrence') ?>" class="tooltip" data-href="<?= $this->url->href('board', 'recurrence', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>"><i class="fa fa-refresh fa-rotate-90 fa-inverse"></i></span>
    <?php endif ?>

    <?php if (! empty($task['nb_links'])): ?>
        <span title="<?= t('Links') ?>" class="tooltip" data-href="<?= $this->url->href('board', 'tasklinks', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>"><i class="fa fa-code-fork"></i>&nbsp;<?= $task['nb_links'] ?></span>
    <?php endif ?>

    <?php if (! empty($task['nb_subtasks'])): ?>
		<span title="<?= t('Sub-Tasks') ?>" class="tooltip" data-href="<?= $this->url->href('board', 'subtasks', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>"><i class="fa fa-bars"></i>&nbsp;<?= round($task['nb_completed_subtasks']/$task['nb_subtasks']*100, 0).'%' ?></span>
    <?php endif ?>

    <?php if (! empty($task['nb_files'])): ?>
        <span title="<?= t('Attachments') ?>" class="tooltip" data-href="<?= $this->url->href('board', 'attachments', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>"><i class="fa fa-paperclip"></i>&nbsp;<?= $task['nb_files'] ?></span>
    <?php endif ?>

    <?php if (! empty($task['nb_comments'])): ?>
        <span title="<?= p($task['nb_comments'], t('%d comment', $task['nb_comments']), t('%d comments', $task['nb_comments'])) ?>" class="tooltip" data-href="<?= $this->url->href('board', 'comments', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>"><i class="fa fa-comment-o"></i>&nbsp;<?= $task['nb_comments'] ?></span>
    <?php endif ?>

    <?php if (! empty($task['description'])): ?>
        <span title="<?= t('Description') ?>" class="tooltip" data-href="<?= $this->url->href('board', 'description', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>">
            <i class="fa fa-file-text-o"></i>
        </span>
    <?php endif ?>

    <?php if ($task['score']): ?>
        <span class="task-score"><?= $this->e($task['score']) ?></span>
    <?php endif ?>

    <?php if (! empty($task['time_estimated'])): ?>
        <span class="task-time-estimated" title="<?= t('Time estimated') ?>"><?= $this->e($task['time_estimated']).'h' ?></span>
    <?php endif ?>

<!--    --><?php //if ($task['is_milestone'] == 1): ?>
<!--        <span title="--><?//= t('Milestone') ?><!--">-->
<!--            <i class="fa fa-flag flag-milestone"></i>-->
<!--        </span>-->
<!--    --><?php //endif ?>
</div>
