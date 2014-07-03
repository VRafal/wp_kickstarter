'use strict';
module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		uglify: {
			options: {
				banner: '/*\n	<%= pkg.name %> - v <%= pkg.version %>\n' + '	date: <%= grunt.template.today("yyyy-mm-dd") %>\n	author: <%= pkg.author %>\n	email: <%= pkg.email %>\n*/\n',
				preserveComments: 'some',
			},
			my_target: {
				files: {
					'webroot/wp-content/themes/<%= pkg.name %>/js/script.min.js': ['webroot/wp-content/themes/<%= pkg.name %>/js/src/*.js']
				}
			}
		},
		less: {
			development: {
				options: {
					cleancss: true
				},
				files: {
					'webroot/wp-content/themes/<%= pkg.name %>/css/main.css': 'webroot/wp-content/themes/<%= pkg.name %>/css/src/*.less'
				}
			}
		},
		watch: {
			scripts: {
				files: ['webroot/wp-content/themes/<%= pkg.name %>/js/src/*.js', 'webroot/wp-content/themes/<%= pkg.name %>/css/src/*.less'],
				tasks: ['uglify', 'less']
			}
		},
		'ftp-deploy': {
			build: {
				auth: {
					host: 'verity.home.pl',
					port: 21,
					authKey: 'key2'
				},
				src: 'webroot/wp-content',
				dest: '/wp-content',
				exclusions: [
					'webroot/wp-content/index.php',
					'webroot/wp-content/themes/twentyfourteen',
					'webroot/wp-content/themes/twentythirteen',
					'webroot/wp-content/themes/twentytwelve',
					'webroot/wp-content/themes/index.php',
					'webroot/wp-content/plugins/akismet',
					'webroot/wp-content/plugins/hello.php',
					'webroot/wp-content/plugins/index.php'
				]
			}
		},
		deployments: {
			options: {
				backups_dir: "backups_db"
			},
			local: {
				"title": "Local DB",
				"database": "db_name",
				"user": "root",
				"pass": "",
				"host": "127.0.0.1",
				"url": ""
			},
			test: {
				"title": "Test DB",
				"database": "db_name",
				"user": "",
				"pass": "",
				"host": "",
				"url": ""
			}
		}
	});

	// Ładowanie zadania
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-ftp-deploy');
	grunt.loadNpmTasks('grunt-deployments');

	// Wystarczy wywoła z linii poleceń 'grunt'. Domyślnym taskiem jest 'watch'
	grunt.registerTask('default', ['watch']);
};
