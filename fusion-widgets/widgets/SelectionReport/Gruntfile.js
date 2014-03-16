module.exports = function(grunt) {
    // Project configuration.
    grunt.initConfig({
        // Directory definitions.
        srcDir: "src",
        libDir: "lib",
        docsDir: "docs",
        buildDir: "build",
        distDir: "dist",
        reportDir: "report",
        testDir: "test",

        // Parse the package.json.
        pkg: grunt.file.readJSON('package.json'),

        // Define and configure tasks.
        clean: ['<%= docsDir %>', '<%= buildDir %>', '<%= distDir %>', '<%= reportDir %>', '<%= pkg.name %>-<%= pkg.version %>.zip', '.grunt'],
        jshint: {
            all: ['Gruntfile.js', '<%= srcDir %>/js/*.js', '<%= testDir %>/js/*.js']
        },
        yuidoc: {
            compile: {
                name: '<%= pkg.name %>',
                description: '<%= pkg.description %>',
                version: '<%= pkg.version %>',
                url: '<%= pkg.homepage %>',
                options: {
                    paths: '<%= srcDir %>/js',
                    outdir: '<%= docsDir %>/'
                }
            }
        },
        jasmine: {
            test: {
                src: '<%= srcDir %>/js/*.js',
                options: {
                    specs: '<%= testDir %>/js/*Spec.js',
                    outfile: '<%= reportDir %>/index.html',
                    keepRunner: 'true',
                    vendor: ['<%= testDir %>/lib/fusion.js', '<%= testDir %>/lib/OpenLayers.js']
                }
            }
        },
        copy: {
            main: {
                files: [{
                    expand: true, flatten: true, src: ['<%= srcDir %>/js/<%= pkg.name %>.js'], dest: '<%= buildDir %>/', filter: 'isFile'
                }, {
                    expand: true, flatten: true, src: ['<%= srcDir %>/<%= pkg.name %>.xml'], dest: '<%= buildDir %>/widgetinfo', filter: 'isFile'
                }, {
                    expand: true, flatten: true, src: ['<%= srcDir %>/php/<%= pkg.name %>.php'], dest: '<%= buildDir %>/<%= pkg.name %>', filter: 'isFile'
                }, {
                    expand: true, cwd: '<%= libDir %>/', src: ['**'], dest: '<%= buildDir %>/<%= pkg.name %>/lib'
                }]
            }
        },
        compress: {
            main: {
                options: {
                    archive: '<%= distDir %>/<%= pkg.name %>-<%= pkg.version %>.zip'
                },
                files: [{
                    expand: true,
                    src: "**/*",
                    cwd: "<%= buildDir %>/"
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
