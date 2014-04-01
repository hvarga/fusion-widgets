/**
 * SelectionReport widget is used to generate a report based on map selections.
 *
 * @class Fusion.Widget.SelectionReport
 */
Fusion.Widget.SelectionReport = OpenLayers.Class(Fusion.Widget, {
    /**
     * uiClass defines Widget UI component.
     *
     * @property uiClass
     * @type {Jx.Button}
     */
    uiClass: Jx.Button,
    reportFormat: null,
    fileName: null,
    scriptUrl: null,

    /**
     * Method is called automatically when the widget needs to be initialized by
     * the platform. When called, it will initialize and enable the
     * SelectionReport widget.
     *
     * @method initializeWidget
     * @param widgetTag
     *            JSON object.
     */
    initializeWidget: function(widgetTag) {
        var json = widgetTag.extension;

        this.reportFormat = json.ReportFormat ? json.ReportFormat[0] : null;
        this.fileName = json.FileName ? json.FileName[0] : null;
        // Set up the base URL which points to the script located on the server side.
        // This script contains the buisiness logic for genereting a selection report.
        this.scriptUrl = Fusion.getFusionURL() + 'widgets/SelectionReport/SelectionReport.php';
        // Initialy the widget must be disabled (there is no map selection).
        this.enable = Fusion.Widget.SelectionReport.prototype.enable;
        // Register for map selection events so that we can enable or disable the widget depending if user selected
        // something or not.
        this.getMap().registerForEvent(Fusion.Event.MAP_SELECTION_ON, OpenLayers.Function.bind(this.enable, this));
        this.getMap().registerForEvent(Fusion.Event.MAP_SELECTION_OFF, OpenLayers.Function.bind(this.disable, this));
    },

    /**
     * This is a callback method which is called when user clicks on a button.
     * This will generate a new report based on map selection.
     * This will prompt user to download the report.
     *
     * @method activate
     */
    activate: function() {
        var widgetLayer = this.getMapLayer();

        var sessionId = widgetLayer.getSessionID();
        var mapName = widgetLayer.getMapName();

        var url = this.scriptUrl;
        var params = [];
        params.push('sessionId=' + sessionId);
        params.push('mapName=' + mapName);
        params.push('reportFormat=' + this.reportFormat);
        params.push('fileName=' + this.fileName);

        url += '?' + params.join('&');
        window.location.href = url;
    },

    /**
     * This method is responisble for enabling or disabling the widget button.
     */
    enable: function() {
        if (this.oMap && this.oMap.hasSelection()) {
            Fusion.Widget.prototype.enable.apply(this, []);
        } else {
            this.disable();
        }
    }
});
