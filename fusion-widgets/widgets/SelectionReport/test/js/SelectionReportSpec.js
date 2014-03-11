describe("SelectionReport", function() {
    it("widget must be defined", function() {
        expect(Fusion.Widget.SelectionReport).toBeDefined();
    });

    it("must have a initializeWidget() method defined", function() {
        expect(Fusion.Widget.SelectionReport.prototype.initializeWidget).toBeDefined();
    });

    it("must have a activate() method defined", function() {
        expect(Fusion.Widget.SelectionReport.prototype.activate).toBeDefined();
    });
});
