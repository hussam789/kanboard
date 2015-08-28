function Spaces() {
    var sp = $("#demo").attr("data-spaces");
    console.log('spaces:' + sp);
    var data;
    if (sp.length == 0) {
        data = {name: "Spaces"};
    } else {
        data = JSON.parse(sp);
    }

    console.log('spaces:' + data);
// define the item component
    Vue.component('item', {
        template: '#item-template',
        data: function () {
            return {
                open: false
            }
        }, watch: {'children': function (childen) {
            console.log(JSON.stringify(childen));
        }},
        computed: {
            isFolder: function () {
                return this.model.children &&
                this.model.children.length
            }
        },
        methods: {
            toggle: function () {
                if (this.isFolder) {
                    this.open = !this.open
                }
            },
            editChild: function () {
                var name = prompt('Enter new name');
                if (name != null && name != "") {
                    this.model.name = name;
                    //this.model.$remove(0);
                }
            },
            removeChild: function (model) {
                data.$remove(model);

            },
            changeType: function () {
                if (!this.isFolder) {
                    this.model.$add('children', [])
                    this.addChild()
                    this.open = true
                }
            },
            addChild: function () {
                var space = prompt("Please enter child space name", "Space");
                if (space != null) {
                    this.model.children.push({
                        name: space
                    })
                }

            }
        }
    })

// boot up the demo
    var demo = new Vue({
        el: '#demo',
        data: {
            treeData: data
        }
    });

    $("#save").click(function () {
        console.log('save' + JSON.stringify(demo.$data.treeData));
        $.ajax({
            cache: false,
            url: $("#demo").attr("data-save-url"),
            contentType: "application/json",
            type: "POST",
            processData: false,
            data: JSON.stringify({
                "project_id": $("#demo").attr("data-project-id"),
                "spaces":JSON.stringify(demo.$data.treeData)
            }),
            success: function(spaces) {
                //data = spaces;
                alert('Saved spaces')
                console.log('data saved');
            }
        });
    });

    function supports_html5_storage() {
        try {
            return 'localStorage' in window && window['localStorage'] !== null;
        } catch (e) {
            return false;
        }
    }
}
