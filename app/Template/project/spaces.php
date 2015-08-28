<!-- ikan -->
<section id="main">

    <div class="page-header">
        <h2><?= t('Space Hierarchy') ?></h2>
        <h4><?= t('* double click to add child space') ?></h4>
    </div>
    <!-- item template -->
    <script type="text/x-template" id="item-template">
        <div id="myfold" v-class="bold: isFolder" v-on="click: toggle, dblclick: changeType">
            {{model.name}}
            <span  v-if="isFolder">[{{open ? '-' : '+'}}]</span>
            <span  v-on="click: editChild()">[edit]</span>
        </div>
        <ul v-show="open" v-if="isFolder">
            <li class="item"
                v-repeat="model: model.children"
                v-component="item">
            </li>
            <li v-on="click: addChild">+</li>

        </ul>
    </script>

    <!-- the demo root element -->
    <ul id="demo"
        data-project-id="<?= $project['id'] ?>"
        data-save-url="<?= $this->url->href('project', 'saveSpaces') ?>"
        data-get-url="<?= $this->url->href('project', 'getSpaces') ?>"
        data-spaces='<?= $spaces ?>'>
        <li class="space-item"
            v-component="item"
            v-with="model: treeData">
        </li>
    </ul>
    <div class="form-actions">
        <input id="save" type="submit" value="<?= t('Save') ?>" class="btn btn-blue"/>
    </div>
</section>

