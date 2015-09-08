<!-- ikan -->
<section id="main">

    <div class="page-header">
        <h2><?= t('Space Hierarchy') ?></h2>
        <h4><?= t('* Double click to add child space') ?></h4>
        <h4><?= t('* Please Type Space abbreviation between Parentheses, example: Level 1 (L1)') ?></h4>
        <h5><?= t('* If you want to use a space only for notation (wont be a associated with tasks) please add @ sign to the beginning of the task, example: @Level1') ?></h5>
    </div>
    <!-- item template -->
    <script type="text/x-template" id="item-template">
        <div id="myfold" v-class="bold: isFolder" v-on="click: toggle, dblclick: changeType">
            {{model.name}}
            <span  v-if="isFolder">[{{open ? '-' : '+'}}]</span>
            <span  v-on="click: editChild(model)">[edit]</span>
        </div>
        <ul v-show="open" v-if="isFolder" >
            <li class="item"
                v-repeat="model: model.children"
                v-component="item">
            </li>
            <li v-on="click: addChild">+</li>

        </ul>
    </script>

    <!-- the demo root element -->
    <ul id="demo"
        style="margin-left: 10px;"
        class="ul-spaces"
        data-project-id="<?= $project['id'] ?>"
        data-save-url="<?= $this->url->href('project', 'saveSpaces') ?>"
        data-get-url="<?= $this->url->href('project', 'getSpaces') ?>"
        data-spaces='<?= $spaces ?>'>
        <li class="item"
            v-component="item"
            v-with="model: treeData">
        </li>
    </ul>
    <div class="form-actions">
        <input id="save" type="submit" value="<?= t('Save') ?>" class="btn btn-blue"/>
    </div>
</section>

