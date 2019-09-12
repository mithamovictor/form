'use strict';

const gulp   = require( 'gulp' );
const sass   = require( 'gulp-sass' );
const concat = require( 'gulp-concat' );
const babel  = require( 'gulp-babel' );


/**
 * Compile CSS
 */

gulp.task( 'css', 
	function() 
	{
		return gulp.src( './src/scss/**/*.scss' )
			.pipe( sass( 
				{
					outputStyle: 'compressed'
				}
			)
			.on( 'error', sass.logError ) )
			.pipe( gulp.dest( './dist/assets/css' ) );
	}
);

/**
 * Compile JS
 */

gulp.task( 'js', 
	function() 
	{
		return gulp.src( './src/js/**/*.js' )
			.pipe(babel({
				presets: ['@babel/env']
			}))
			.pipe( concat( 'app.js' ) )
			.pipe( gulp.dest( './dist/assets/js' ) );
	}
);


/**
 * Watch for changes
 */

gulp.task( 'watch', 
	function() 
	{
		gulp.watch( './src/scss/**/*.scss', gulp.parallel( 'css' ) );
		gulp.watch( './src/js/**/*.js', gulp.parallel( 'js' ) );
	}
);


/**
 * Build function
 */
  
gulp.task( 'build', 
	gulp.parallel(
		'css',
		'outputCss',
		'js'
	)
);