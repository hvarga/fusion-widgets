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

    map: null,
    widgetLayer: null,

    /**
     * Method is called automatically when the widget needs to be initialized by the platform.
     * When called, it will initialize and enable the SelectionReport widget.
     *
     * @method initializeWidget
     * @param widgetTag JSON object.
     */
    initializeWidget: function(widgetTag) {
        // Enable the widget so that it can be used by the user.
        //this.sBaseUrl = Fusion.getFusionURL() + 'widgets/SelectionReport/SelectionReport.php';
        this.sBaseUrl = 'widgets/SelectionReport/SelectionReport.php';
        this.enable();
    },

    /**
     * This is a callback method which is called when user clicks on a button.
     * This will generate a new report based on map selection. The report can be downloaded to the user machine.
     *
     * @method activate
     */
    activate: function() {
        widgetLayer = this.getMapLayer();

        //var url = 'widgets/SelectionReport/SelectionReport.php';
        var url = Fusion.getFusionURL() + 'widgets/SelectionReport/SelectionReport.php';
        
        
        var sessionID = widgetLayer.getSessionID();
        var mapName = widgetLayer.getMapName();
        /*
        var params = [];
        params.push('locale=' + Fusion.locale);
        params.push('sessionId=' + widgetLayer.getSessionID());
        params.push('mapName=' + widgetLayer.getMapName());
        
        url += params.join('&');
        
        window.open(url);
        */
        
        /*
        var opts = {
              parameters: {
                  locale: Fusion.locale,
                  sessionId: widgetLayer.getSessionID(),
                  mapName: widgetLayer.getMapName()
              },
              
              onComplete: OpenLayers.Function.bind(this.downloadReady, this)
          };
          Fusion.ajaxRequest(url, opts);
          */
          
          /*
          var request = OpenLayers.Request.GET({
                url: Fusion.getFusionURL() + 'widgets/SelectionReport/SelectionReport.php',
                params: {
                  locale: Fusion.locale,
                  sessionId: widgetLayer.getSessionID(),
                  mapName: widgetLayer.getMapName()
              },
                async: false
            });
            // do something with the response
            alert('ok');
            */
            var params = [];
            params.push('locale=' + Fusion.locale);
            params.push('sessionId=' + widgetLayer.getSessionID());
            params.push('mapName=' + widgetLayer.getMapName());
            
            url += '?' + params.join('&');
            window.location.href = url;
          
          
        /*
        // Get the reference to the widget responsible for rendering of the map.
        map = Fusion.getWidgetById("Map");
        // Now that we have a referece to the map widget, we need to get the selected parcels.
        // This is acomplished using the getSelection() of the Fusion.Widget object.
        // The getSelection() method is an asynchronous call so we need to register a callback method.
        // The callback method will be called when getSelection() finishes its processing.
        map.getSelection(this.displaySelection);
       */
        
    },
    
    downloadReady: function(data) {
        //alert("Call to PHP script complete.");
        // Download the CSV file.
        /*
        var a = window.document.createElement('a');
        a.href = window.URL.createObjectURL(new Blob([data], {type: 'application/vnd.ms-excel'}));
        a.download = 'report.xls';

        // Append anchor to body.
        document.body.appendChild(a);
        a.click();

        // Remove anchor from body
        document.body.removeChild(a);
        */
        //window.location = Fusion.getFusionURL() + 'widgets/SelectionReport/SelectionReport.php';
    },

    /**
     * This is a callback method which will be called when the map widget finishes the processing of the getSelection()
     * method. This will give us a selection object which will contain all the selected parcels from the map.
     *
     * @method displaySelection
     * @param selection Object which will contain all the selected parcels from the map widget.
     */
    displaySelection: function(selection) {
    
        
          
        /*  
        // We need to read the map name from the selection object.
        // There can be actually multiple maps in one selection object. Which is kind of weird, since the map
        // widget itself can only render one map at a time. So, I will only take the first map.
        // The rest (if this is possible) will be ignored.
        var mapSelection = null;
        for (var mapName in selection) {
            mapSelection = selection[mapName];
            break;
        }

        // Define a variable which will hold the collected data that user needs to download.
        var collectedData = "";
        // Since map can contain selections from the multiple layers, we need to iterate over each layer.
        var numberOfLayers = mapSelection.getNumLayers();
        for (var layerNum = 0; layerNum < numberOfLayers; layerNum++) {
            var layer = mapSelection.getLayer(layerNum);
            var layerName = layer.getName();

            collectedData = collectedData.concat(layerName, '\n');

            // Layer can also have multiple features and we need to iterate over each one.
            var numberOfSelectedElements = layer.getNumElements();
            var layerPropertyNames = layer.getPropertyNames();
            var numberOfLayerProperties = layer.getNumProperties();

            // Add the layer property names as a columns in collectedData.
            for (propertyNum = 0; propertyNum < numberOfLayerProperties; propertyNum++) {
                var layerPropertyName = layerPropertyNames[propertyNum];
                collectedData = collectedData.concat(layerPropertyName);

                if (propertyNum != numberOfLayerProperties - 1) {
                    collectedData = collectedData.concat(",");
                }
            }

            collectedData = collectedData.concat("\n");

            if (numberOfSelectedElements > 0) {
                // Get each selected element.
                for (var elementNumber = 0; elementNumber < numberOfSelectedElements; elementNumber++) {
                    // Now that we have an element, read its properties. Element can have multiple properties
                    // so we need to iterate over each one and read its value.
                    for (var propertyNum = 0; propertyNum < numberOfLayerProperties; propertyNum++) {
                        var propertyName = layerPropertyNames[propertyNum];
                        var propertyValue = layer.getElementValue(elementNumber, propertyNum);

                        // Decode HTML entity.
                        var t = window.document.createElement('textarea');
                        t.innerHTML = propertyValue;
                        var v = t.value;

                        collectedData = collectedData.concat(v);

                        if (propertyNum != numberOfLayerProperties - 1) {
                            collectedData = collectedData.concat(",");
                        }
                    }

                    collectedData = collectedData.concat("\n");
                }
            }
        }

        // Download the CSV file.
        var a = window.document.createElement('a');
        a.href = window.URL.createObjectURL(new Blob([collectedData], {type: 'text/csv'}));
        a.download = 'report.csv';

        // Append anchor to body.
        document.body.appendChild(a);
        a.click();

        // Remove anchor from body
        document.body.removeChild(a);
        */
    }
});
