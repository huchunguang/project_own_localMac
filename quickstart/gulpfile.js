//on 2017-05-01 using generator-webapp 
//author:Chunguang.Hu 
//Package: Node-npm 
//相关Plugins的说明请参看Npm Link:https://www.npmjs.com/package/gulp-run-sequence
//相关插件版本以及说明请查看npm package.json的描述
//引入Laravel自带的平滑Gulp API接口 Exlixir
var elixir = require('laravel-elixir');
//引入Gulp前端自动化管理工具
var gulp   = require('gulp');
const getLoadPlugins = require('gulp-load-plugins');
//引入Gulp插件自动加载机制
const $ = getLoadPlugins();
//引入浏览器同步工具browserSync
const browserSync = require('browser-sync').create();
//引入删除文件以及文件夹插件
const del = require('del');
//https://github.com/taptapship/wiredep#bower-overrides
const wiredep = require('wiredep').stream;
//引入指定的顺序运行一系列Gulp定义的任务插件,如果指定第三个参数为回调函数,那么当任务完成或者任务发生错误时回调此函数
const runSequence = require('run-sequence');
const pkg = require('./package.json');
const reload = browserSync.reload;
//暂定当前环境为Dev开发环境
var dev = true;
//引入JS压缩工具uglify 
var uglify = require('gulp-uglify');
elixir(function(mix) {
    mix.sass('app.scss').browserify('app.js');
});
//请在这里配置你的Gulp默认启动项
gulp.task('default',function(){

});
//定义JS文件压缩任务,压缩后的文件放在根目录public/js目录下
gulp.task('uglify',() => {
    gulp.src('./resources/assets/js/dist/*.js').pipe(uglify()).pipe(gulp.dest('./public/js'));
});
//定义同步编译Sass文件的任务使用Gulp-sourceMap,并自动填充浏览器兼容性属性前缀
gulp.task('styles',() => {
    return   gulp.src('./resources/assets/sass/styles/*.scss').pipe($.plumber()).pipe($.sass.sync({
            outputStyle: 'expanded'
        }).on('error',$.sass.logError))
        .pipe($.autoprefixer({
            browsers: ['> 1%', 'last 2 versions', 'Firefox ESR']
        }))
        .pipe($.sourcemaps.write('./maps'))
        .pipe(gulp.dest('./public/css/styles'))
        .pipe(reload({
            stream: true 
        }));
});
function lint(files,options)
{
    return gulp.src(files)
    .pipe($.eslint({
                
                fix: true
            }))
            .pipe($.eslint.format())
            .pipe($.eslint.failAfterError());
}
gulp.task('jsLint',() => {
   return lint('./resources/assets/js/dist/*.js')
            .pipe(gulp.dest('./public/js'));
});
//定义Laravel图片文件压缩任务,针对不同图片类型不同压缩设置,API以及具体设置参数请查看:Link:https://github.com/sindresorhus/gulp-imagemin#user-content-options
gulp.task('app:imagemin',() => {
        gulp.src('./public/uploadsfiles/images/*.{png,jpeg,jpg,gif}')
        .pipe($.cache(
            $.imagemin([
                $.imagemin.gifsicle({interlaced: true}),
                $.imagemin.jpegtran({progressive: true}),
                $.imagemin.optipng({optimizationLevel: 3,colorTypeReduction: true})
                     ])
        ))
        .pipe(gulp.dest('./public/uploadsfiles/images/minify'));
});
//默认项目开始时执行一下Task，并监听指定文件目录修改执行Gulp任务，开启Proxy，自动刷新浏览器
//按照你本地的Nginx配置BroswerSync的Proxy配置，以及监听JS和Scss的目录
gulp.task('app:start',() => {
runSequence(['uglify','styles'],['jsLint'],() => {
//开启浏览器监听,默认的9000端口,可根据需要设置其他端口监听文件改变刷新浏览器Link:http://www.browsersync.cn/docs/gulp/
browserSync.init({
            notify:false,
            port: 9000,
            proxy: "http://www.laravel_intermediate.com/tasks",
        });
        gulp.watch(['./resources/assets/js/dist/*.js',
                    './resources/assets/sass/styles/*.scss',
                    './resources/assets/sass/styles/*.css'])
                    .on('change',reload);
        gulp.watch('./resources/assets/sass/styles/*scss',['styles']);
        gulp.watch('./resources/assets/js/dist/*js',['uglify','jsLint']);
        gulp.watch('./public/uploadsfiles/images/*.{png,jpeg,jpg,gif}',['app:imagemin']);
    });    

});
