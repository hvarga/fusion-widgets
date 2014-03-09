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

    /**
     * Method is called automatically when the widget needs to be initialized by the platform.
     * When called, it will initialize and enable the SelectionReport widget.
     *
     * @method initializeWidget
     * @param widgetTag JSON object.
     */
    initializeWidget: function(widgetTag) {
        // Enable the widget so that it can be used by the user.
        this.enable();
    },

    /**
     * This is a callback method which is called when user clicks on a button.
     * This will generate a new report based on map selection. The report can be downloaded to the user machine.
     *
     * @method activate
     */
    activate: function() {
        // TODO: Implement the generating of report.
    }
});