# Documentation

- Home page: <http://mapguide.osgeo.org/>
- Link to CentOS installation script: <http://download.osgeo.org/mapguide/releases/2.5.2/mginstallcentos.sh>
- MapGuide Maestro: <http://trac.osgeo.org/mapguide/wiki/maestro>
- FDO Data Access Technology: <http://fdo.osgeo.org/>
- Getting Started: <http://mapguide.osgeo.org/GettingStartedGuide.html>
- Application Development: <http://mapguide.osgeo.org/sites/mapguide.osgeo.org/files/MgOpenSourceDevGuide.pdf>
- Samples: <http://mapguide.osgeo.org/download/releases/2.0.x-samples>
- Fusion Learning Materials: <http://mapguide.osgeo.org/sites/mapguide.osgeo.org/files/FusionLearningMaterialsforMapGuide.zip>
- Autodesk MapGuide Enterprise 2010 Developer’s Guide: <http://images.autodesk.com/adsk/files/mapguide_enterprise_2010_devlopers_guide.pdf>
- MapGuide Open Source Web API Reference: <http://mapguide.osgeo.org/files/mapguide/docs/2.0/index.htm>
- MapGuide Architecture: <http://trac.osgeo.org/mapguide/wiki/MapGuideArchitecture>
- Fusion: <http://trac.osgeo.org/fusion/>
- MapGuide Developer’s Guide: <http://localhost/mapguide/help/devguide/index.html>
- Fusion MapGuide Tutorial: <http://trac.osgeo.org/fusion/wiki/MapGuideTutorial>
- How To Customize SelectionPanel widget: <http://trac.osgeo.org/fusion/wiki/HowToCustomizeSelectionPanel>

# Hello World Widget

All widgets are installed in the `<MAPGUIDE_INSTALLATION_FOLDER>\www\fusion\widgets`.
A widget is composed of several files. At bare minimum, there must be a JavaScript file responsible for the client-side interaction and the widget information file which is used by the MapGuide Open Source to describe the widget. Optionally, there can be a number of PHP files which are backing the client-side business logic.

## JavaScript File

JavaScript file contains a class definition for the widget and its business logic. All widgets must inherit from the base class `Fusion.Widget`.
At the bare minimum an `initializeWidget()` and an `activate()` methods are required to be implemented.

```js
/**
* Class: Fusion.Widget.HelloWorld
*
* HelloWorld widget to display a pop-up dialog showing a "Hello World" message.
*
* Inherits from:
*  - <Fusion.Widget>
*/
Fusion.Widget.HelloWorld = OpenLayers.Class(Fusion.Widget, {
    uiClass: Jx.Button,

    /**
    * Function: initializeWidget
    *
    * Method is called automatically when the widget needs to be initialized by the platform.
    * When called, it will enable the HelloWorld widget.
    */
    initializeWidget: function(widgetTag) {
        this.enable();
    },

    /**
    * Function: activate
    *
    * This is a callback method which is called when user clicks on a button.
    * This will open a pop-up dialog showing a "Hello World" message on screen.
    */
    activate: function() {
        alert("Hello World");
    }
});
```

The JavaScript file is named after the class name and it must match. In this example, the class name is `Fusion.Widget.HelloWorld` and the file name is `HelloWorld.js`.

## Widget Information File

The widget information file defines a master widget type and is read by the MapGuide Open Source. The file actually describes the widget to the MapGuide Open Source and is also used to create instances of the widget. From this file MapGuide Open Source can read the widget name, its description, tooltip when user hovers over it, icon which will be drawn for the widget and more. Besides the informational data, the file can also describe the widget parameters which can be later use in MapGuide Maestro to tune and configure the widget.

```xml
<WidgetInfo>
    <Type>HelloWorld</Type>
    <LocalizedType>HelloWorld</LocalizedType>
    <Description>This is a simple Hello World widget</Description>
    <Location></Location>
    <Label>HelloWorld</Label>
    <Tooltip>Click to show Hello World message on screen</Tooltip>
    <StatusText></StatusText>
    <ImageUrl>images/icons/edit-xml.png</ImageUrl>
    <ImageClass>helloworld</ImageClass>
    <StandardUi>true</StandardUi>
    <ContainableBy>Any</ContainableBy>
</WidgetInfo>
```

The element `Type` must match the name of the corresponding JavaScript file.
