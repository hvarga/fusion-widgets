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
        // Set up the base URL which points to the script located on the server side.
        // This script contains the buisiness logic for genereting a selection report.
        this.scriptUrl = Fusion.getFusionURL() + 'widgets/SelectionReport/SelectionReport.php';
        // Enable the widget so that it can be used by the user.
        this.enable();
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

        url += '?' + params.join('&');
        window.location.href = url;
    }
});
