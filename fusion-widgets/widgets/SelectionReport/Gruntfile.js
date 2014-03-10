module.exports = function(grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        clean: ['docs', 'build', 'dist', 'report', '<%= pkg.name %>-<%= pkg.version %>.zip', '.grunt'],
        jshint: {
            all: ['Gruntfile.js', 'src/js/*.js', 'test/js/*.js']
        },
        yuidoc: {
            compile: {
                name: '<%= pkg.name %>',
                description: '<%= pkg.description %>',
                version: '<%= pkg.version %>',
                url: '<%= pkg.homepage %>',
                options: {
                    paths: 'src/js',
                    outdir: 'docs/'
                }
            }
        },
        jasmine: {
            test: {
                src: 'src/js/*.js',
                options: {
                    specs: 'test/js/*Spec.js',
                    outfile: 'report/index.html',
                    keepRunner: 'true',
                    vendor: ['test/lib/fusion.js', 'test/lib/OpenLayers.js']
                }
            }
        },
        copy: {
            main: {
                files: [{
                    expand: true, flatten: true, src: ['src/js/<%= pkg.name %>.js'], dest: 'dist/', filter: 'isFile'
                }, {
                    expand: true, flatten: true, src: ['src/<%= pkg.name %>.xml'], dest: 'dist/widgetinfo', filter: 'isFile'
                }]
            }
        },
        compress: {
            main: {
                options: {
                    archive: '<%= pkg.name %>-<%= pkg.version %>.zip'
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

    // Load the plugin that provides the "yuidoc" task.
    grunt.loadNpmTasks('grunt-contrib-yuidoc');

    // Load the plugin that provides the "jasmine" task.
    grunt.loadNpmTasks('grunt-contrib-jasmine');

    // Load the plugin that provides the "copy" task.
    grunt.loadNpmTasks('grunt-contrib-copy');

    // Load the plugin that provides the "compress" task.
    grunt.loadNpmTasks('grunt-contrib-compress');

    // Default task(s).
    grunt.registerTask('default', ['clean', 'jshint', 'yuidoc', 'jasmine', 'copy', 'compress']);
};