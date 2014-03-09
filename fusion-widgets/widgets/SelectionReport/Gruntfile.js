module.exports = function(grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        clean: ['build', 'dist', 'SelectionReport.zip'],
        jshint: {
            all: ['Gruntfile.js', 'src/js/*.js', 'test/js/*.js']
        },
        copy: {
            main: {
                files: [{
                    expand: true, flatten: true, src: ['src/js/SelectionReport.js'], dest: 'dist/', filter: 'isFile'
                }, {
                    expand: true, flatten: true, src: ['src/SelectionReport.xml'], dest: 'dist/widgetinfo', filter: 'isFile'
                }]
            }
        },
        compress: {
            main: {
                options: {
                    archive: 'SelectionReport.zip'
                },
                files: [{
                    expand: true,
                    src: "**/*",
                    cwd: "dist/"
                }]
            }
        }
    });

    // Load the plugin that provides the "clean" task.
    grunt.loadNpmTasks('grunt-contrib-clean');

    // Load the plugin that provides the "jshint" task.
    grunt.loadNpmTasks('grunt-contrib-jshint');

    // Load the plugin that provides the "copy" task.
    grunt.loadNpmTasks('grunt-contrib-copy');

    // Load the plugin that provides the "compress" task.
    grunt.loadNpmTasks('grunt-contrib-compress');

    // Default task(s).
    grunt.registerTask('default', ['clean', 'jshint', 'copy', 'compress']);
};