/**
 * Class: Fusion.Widget.SelectionReport
 *
 * SelectionReport widget is used to generate a report based on map selections.
 *
 * Inherits from:
 *  - <Fusion.Widget>
 */
Fusion.Widget.SelectionReport = OpenLayers.Class(Fusion.Widget, {
    uiClass : Jx.Button,

    /**
     * Function: initializeWidget
     *
     * Method is called automatically when the widget needs to be initialized by the platform.
     * When called, it will initialize and enable the SelectionReport widget.
     */
    initializeWidget : function(widgetTag) {
        // Enable the widget so that it can be used by the user.
        this.enable();
    },

    /**
     * Function: activate
     *
     * This is a callback method which is called when user clicks on a button.
     * This will generate a new report based on map selection. The report can be downloaded to the user machine.
     */
    activate : function() {
        // TODO: Implement the generating of report.
    }
});