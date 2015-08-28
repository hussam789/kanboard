<!-- ikan -->
<section id="main">
    <section>
        <h3><?= t('Change space for the task "%s"', $values['title']) ?></h3>
        <form method="post" action="<?= $this->u('board', 'updateSpace', array('task_id' => $values['id'], 'project_id' => $values['project_id'])) ?>">

            <?= $this->formCsrf() ?>

            <?= $this->formHidden('id', $values) ?>
            <?= $this->formHidden('project_id', $values) ?>

            <?= $this->formLabel(t('Space'), 'spaces') ?>
            <?= $this->formSelect('spaces', $spaces, $values) ?><br/>

            <div class="form-actions">
                <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue"/>
                <?= t('or') ?>
                <?= $this->a(t('cancel'), 'board', 'show', array('project_id' => $project['id']), false, 'close-popover') ?>
            </div>
        </form>
    </section>

</section>