(function() {
    'use strict';

    pimcore.settings.targeting.conditions.register(
        'cmf_has_segment',
        Class.create(pimcore.settings.targeting.condition.abstract, {
            getName: function () {
                return t("plugin_cmf_targeting_condition_has_segment");
            },

            getPanel: function (panel, data) {
                var id = Ext.id();

                var getDataValue = function(key, defaultValue) {
                    if ('undefined' === typeof data[key]) {
                        return defaultValue;
                    }

                    return data[key];
                };

                return new Ext.form.FormPanel({
                    id: id,
                    forceLayout: true,
                    style: "margin: 10px 0 0 0",
                    bodyStyle: "padding: 10px 30px 10px 30px; min-height:40px;",
                    tbar: pimcore.settings.targeting.conditions.getTopBar(this, id, panel, data),
                    items: [
                        {
                            xtype: "combo",
                            fieldLabel: t('plugin_cmf_segment'),
                            name: "segmentId",
                            displayField: "name",
                            valueField: "id",
                            store: pimcore.globalmanager.get("cmf.segment_store"),
                            editable: false,
                            width: 400,
                            triggerAction: 'all',
                            listWidth: 200,
                            mode: "local",
                            value: data.segmentId,
                            emptyText: t("plugin_cmf_select_a_segment")
                        },
                        {
                            name: "considerTrackedSegments",
                            fieldLabel: t("plugin_cmf_consider_tracked_segments"),
                            xtype: "checkbox",
                            checked: getDataValue('considerTrackedSegments', true)
                        },
                        {
                            name: "considerCustomerSegments",
                            fieldLabel: t("plugin_cmf_consider_customer_segments"),
                            xtype: "checkbox",
                            checked: getDataValue('considerCustomerSegments', true)
                        },
                        {
                            xtype: 'numberfield',
                            fieldLabel: t("threshold"),
                            name: "threshold",
                            value: getDataValue('threshold', 1),
                            width: 200,
                            minValue: 1,
                            allowDecimals: false
                        },
                        {
                            xtype: "hidden",
                            name: "type",
                            value: "cmf_has_segment"
                        }
                    ]
                });
            }
        })
    );
}());
