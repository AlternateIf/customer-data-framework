(function() {
    'use strict';

    pimcore.settings.targeting.actions.register(
        'cmf_track_segment',
        Class.create(pimcore.settings.targeting.action.abstract, {
            getName: function () {
                return t("plugin_cmf_targeting_action_track_segment");
            },

            getPanel: function (panel, data) {
                var id = Ext.id();

                return new Ext.form.FormPanel({
                    id: id,
                    forceLayout: true,
                    style: "margin: 10px 0 0 0",
                    bodyStyle: "padding: 10px 30px 10px 30px; min-height:40px;",
                    tbar: pimcore.settings.targeting.actions.getTopBar(this, id, panel),
                    items: [
                        {
                            name: "segment",
                            fieldLabel: t('segment'),
                            xtype: "textfield",
                            width: 600,
                            cls: "input_drop_target",
                            value: data.segment,
                            listeners: {
                                "render": function (el) {
                                    new Ext.dd.DropZone(el.getEl(), {
                                        reference: this,
                                        ddGroup: "element",
                                        getTargetFromEvent: function (e) {
                                            return this.getEl();
                                        }.bind(el),

                                        onNodeOver: function (target, dd, e, data) {
                                            data = data.records[0].data;

                                            if (data.type !== 'object') {
                                                return Ext.dd.DropZone.prototype.dropNotAllowed;
                                            }

                                            if (data.className !== 'CustomerSegment') {
                                                return Ext.dd.DropZone.prototype.dropNotAllowed;
                                            }

                                            return Ext.dd.DropZone.prototype.dropAllowed;
                                        },

                                        onNodeDrop: function (target, dd, e, data) {
                                            data = data.records[0].data;

                                            if (data.type !== 'object') {
                                                return false;
                                            }

                                            if (data.className !== 'CustomerSegment') {
                                                return false;
                                            }

                                            this.setValue(data.path);
                                            return true;
                                        }.bind(el)
                                    });
                                }
                            }
                        },
                        {
                            xtype: "hidden",
                            name: "type",
                            value: "cmf_track_segment"
                        }
                    ]
                });
            }
        })
    );

    pimcore.settings.targeting.actions.register(
        "assign_target_group",
        Class.create(pimcore.settings.targeting.action.abstract, {
            getName: function () {
                return t('assign_target_group');
            },

            getPanel: function (panel, data) {
                var id = Ext.id();

                return new Ext.form.FormPanel({
                    id: id,
                    forceLayout: true,
                    style: "margin: 10px 0 0 0",
                    labelWidth: 200,
                    bodyStyle: "padding: 10px 30px 10px 30px; min-height:40px;",
                    tbar: pimcore.settings.targeting.actions.getTopBar(this, id, panel),
                    items: [
                        {
                            xtype: "combo",
                            fieldLabel: t('target_group'),
                            name: "targetGroup",
                            labelWidth: 200,
                            displayField: 'text',
                            valueField: "id",
                            store: pimcore.globalmanager.get("target_group_store"),
                            editable: false,
                            width: 500,
                            triggerAction: 'all',
                            listWidth: 200,
                            mode: "local",
                            value: data.targetGroup,
                            emptyText: t("select_a_target_group")
                        },
                        {
                            xtype: 'numberfield',
                            fieldLabel: t('assign_target_group_weight'),
                            name: "weight",
                            labelWidth: 200,
                            value: data.weight ? data.weight : 1,
                            width: 300,
                            minValue: 1,
                            allowDecimals: false
                        },
                        {
                            fieldLabel: t("plugin_cmf_targetingaction_assign_segments"),
                            xtype: "combobox",
                            labelWidth: 200,
                            name: "assignSegment",
                            width: 500,
                            store: Ext.data.ArrayStore({
                                fields: ['assignSegment', 'assignSegmentTranslated'],
                                data: [
                                    ['no', t('plugin_cmf_targetingaction_assign_segments_no')],
                                    ['assign_only', t('plugin_cmf_targetingaction_assign_segments_assign_only')],
                                    ['assign_consider_weight', t('plugin_cmf_targetingaction_assign_segments_assign_consider_weight')]
                                ]
                            }),
                            value: data.assignSegment ? data.assignSegment : 'no',
                            displayField: 'assignSegmentTranslated',
                            valueField: 'assignSegment'
                        },
                        {
                            xtype: "checkbox",
                            labelWidth: 200,
                            name: "trackActivity",
                            fieldLabel: t("plugin_cmf_targetingaction_track_activity"),
                            checked: data.trackActivity
                        },
                        {
                            xtype: "hidden",
                            name: "type",
                            value: "assign_target_group"
                        }
                    ]
                });
            }
        })
    );

}());
