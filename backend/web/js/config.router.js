'use strict';

/**
 * Config for the router
 */
angular.module('app')
    .run(
    ['$rootScope', '$state', '$stateParams',
        function ($rootScope, $state, $stateParams) {
            $rootScope.$state = $state;
            $rootScope.$stateParams = $stateParams;
        }
    ]
)
    .config(
    ['$stateProvider', '$urlRouterProvider',
        function ($stateProvider, $urlRouterProvider) {

            $urlRouterProvider
                .otherwise('/app/dashboard-v1');
            $stateProvider
                .state('app', {
                    abstract: true,
                    url: '/app',
                    templateUrl: 'tpl/app.html'
                })
                .state('app.dashboard-v1', {
                    url: '/dashboard-v1',
                    templateUrl: 'tpl/app_dashboard_v1.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/chart.js']);
                            }]
                    }
                })
                .state('app.dashboard-v2', {
                    url: '/dashboard-v2',
                    templateUrl: 'tpl/app_dashboard_v2.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/chart.js']);
                            }]
                    }
                })
                .state('app.ui', {
                    url: '/ui',
                    template: '<div ui-view class="fade-in-up"></div>'
                })
                .state('app.ui.buttons', {
                    url: '/buttons',
                    templateUrl: 'tpl/ui_buttons.html'
                })
                .state('app.ui.icons', {
                    url: '/icons',
                    templateUrl: 'tpl/ui_icons.html'
                })
                .state('app.ui.grid', {
                    url: '/grid',
                    templateUrl: 'tpl/ui_grid.html'
                })
                .state('app.ui.widgets', {
                    url: '/widgets',
                    templateUrl: 'tpl/ui_widgets.html'
                })
                .state('app.ui.bootstrap', {
                    url: '/bootstrap',
                    templateUrl: 'tpl/ui_bootstrap.html'
                })
                .state('app.ui.sortable', {
                    url: '/sortable',
                    templateUrl: 'tpl/ui_sortable.html'
                })
                .state('app.ui.portlet', {
                    url: '/portlet',
                    templateUrl: 'tpl/ui_portlet.html'
                })
                .state('app.ui.timeline', {
                    url: '/timeline',
                    templateUrl: 'tpl/ui_timeline.html'
                })
                .state('app.ui.tree', {
                    url: '/tree',
                    templateUrl: 'tpl/ui_tree.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('angularBootstrapNavTree').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/tree.js');
                                    }
                                );
                            }
                        ]
                    }
                })
                .state('app.ui.toaster', {
                    url: '/toaster',
                    templateUrl: 'tpl/ui_toaster.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('toaster').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/toaster.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.ui.jvectormap', {
                    url: '/jvectormap',
                    templateUrl: 'tpl/ui_jvectormap.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('js/controllers/vectormap.js');
                            }]
                    }
                })
                .state('app.ui.googlemap', {
                    url: '/googlemap',
                    templateUrl: 'tpl/ui_googlemap.html',
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load([
                                    'js/app/map/load-google-maps.js',
                                    'js/app/map/ui-map.js',
                                    'js/app/map/map.js']).then(
                                    function () {
                                        return loadGoogleMaps();
                                    }
                                );
                            }]
                    }
                })
                .state('app.chart', {
                    url: '/chart',
                    templateUrl: 'tpl/ui_chart.html',
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load('js/controllers/chart.js');
                            }]
                    }
                })
                // table
                .state('app.table', {
                    url: '/table',
                    template: '<div ui-view></div>'
                })
                .state('app.table.static', {
                    url: '/static',
                    templateUrl: 'tpl/table_static.html'
                })
                .state('app.table.datatable', {
                    url: '/datatable',
                    templateUrl: 'tpl/table_datatable.html'
                })
                .state('app.table.footable', {
                    url: '/footable',
                    templateUrl: 'tpl/table_footable.html'
                })
                .state('app.table.grid', {
                    url: '/grid',
                    templateUrl: 'tpl/table_grid.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/grid.js');
                                    }
                                );
                            }]
                    }
                })
                // form
                .state('app.form', {
                    url: '/form',
                    template: '<div ui-view class="fade-in"></div>',
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load('js/controllers/form.js');
                            }]
                    }
                })
                .state('app.form.elements', {
                    url: '/elements',
                    templateUrl: 'tpl/form_elements.html'
                })

                .state('app.form.validation', {
                    url: '/validation',
                    templateUrl: 'tpl/form_validation.html'
                })
                .state('app.form.wizard', {
                    url: '/wizard',
                    templateUrl: 'tpl/form_wizard.html'
                })
                .state('app.form.fileupload', {
                    url: '/fileupload',
                    templateUrl: 'tpl/form_fileupload.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('angularFileUpload').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/file-upload.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.form.imagecrop', {
                    url: '/imagecrop',
                    templateUrl: 'tpl/form_imagecrop.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngImgCrop').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/imgcrop.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.form.select', {
                    url: '/select',
                    templateUrl: 'tpl/form_select.html',
                    controller: 'SelectCtrl',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ui.select').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/select.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.form.slider', {
                    url: '/slider',
                    templateUrl: 'tpl/form_slider.html',
                    controller: 'SliderCtrl',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('vr.directives.slider').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/slider.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.form.editor', {
                    url: '/editor',
                    templateUrl: 'tpl/form_editor.html',
                    controller: 'EditorCtrl',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('textAngular').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/editor.js');
                                    }
                                );
                            }]
                    }
                })
                // pages
                .state('app.page', {
                    url: '/page',
                    template: '<div ui-view class="fade-in-down"></div>'
                })
                .state('app.page.profile', {
                    url: '/profile',
                    templateUrl: 'tpl/page_profile.html'
                })
                .state('app.page.post', {
                    url: '/post',
                    templateUrl: 'tpl/page_post.html'
                })
                .state('app.page.search', {
                    url: '/search',
                    templateUrl: 'tpl/page_search.html'
                })
                .state('app.page.invoice', {
                    url: '/invoice',
                    templateUrl: 'tpl/page_invoice.html'
                })
                .state('app.page.price', {
                    url: '/price',
                    templateUrl: 'tpl/page_price.html'
                })
                .state('app.docs', {
                    url: '/docs',
                    templateUrl: 'tpl/docs.html'
                })
                // others
                .state('lockme', {
                    url: '/lockme',
                    templateUrl: 'tpl/page_lockme.html'
                })
                .state('access', {
                    url: '/access',
                    template: '<div ui-view class="fade-in-right-big smooth"></div>'
                })


                .state('access.signup', {
                    url: '/signup',
                    templateUrl: 'tpl/page_signup.html',
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load(['js/controllers/signup.js']);
                            }]
                    }
                })
                .state('access.forgotpwd', {
                    url: '/forgotpwd',
                    templateUrl: 'tpl/page_forgotpwd.html'
                })
                .state('access.404', {
                    url: '/404',
                    templateUrl: 'tpl/page_404.html'
                })

                // fullCalendar
                .state('app.calendar', {
                    url: '/calendar',
                    templateUrl: 'tpl/app_calendar.html',
                    // use resolve to load other dependences
                    resolve: {
                        deps: ['$ocLazyLoad', 'uiLoad',
                            function ($ocLazyLoad, uiLoad) {
                                return uiLoad.load(
                                    ['vendor/jquery/fullcalendar/fullcalendar.css',
                                        'vendor/jquery/fullcalendar/theme.css',
                                        'vendor/jquery/jquery-ui-1.10.3.custom.min.js',
                                        'vendor/libs/moment.min.js',
                                        'vendor/jquery/fullcalendar/fullcalendar.min.js',
                                        'js/app/calendar/calendar.js']
                                ).then(
                                    function () {
                                        return $ocLazyLoad.load('ui.calendar');
                                    }
                                )
                            }]
                    }
                })

                // mail
                .state('app.mail', {
                    abstract: true,
                    url: '/mail',
                    templateUrl: 'tpl/mail.html',
                    // use resolve to load other dependences
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load(['js/app/mail/mail.js',
                                    'js/app/mail/mail-service.js',
                                    'vendor/libs/moment.min.js']);
                            }]
                    }
                })
                .state('app.mail.list', {
                    url: '/inbox/{fold}',
                    templateUrl: 'tpl/mail.list.html'
                })
                .state('app.mail.detail', {
                    url: '/{mailId:[0-9]{1,4}}',
                    templateUrl: 'tpl/mail.detail.html'
                })
                .state('app.mail.compose', {
                    url: '/compose',
                    templateUrl: 'tpl/mail.new.html'
                })

                .state('layout', {
                    abstract: true,
                    url: '/layout',
                    templateUrl: 'tpl/layout.html'
                })
                .state('layout.fullwidth', {
                    url: '/fullwidth',
                    views: {
                        '': {
                            templateUrl: 'tpl/layout_fullwidth.html'
                        },
                        'footer': {
                            templateUrl: 'tpl/layout_footer_fullwidth.html'
                        }
                    },
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load(['js/controllers/vectormap.js']);
                            }]
                    }
                })
                .state('layout.mobile', {
                    url: '/mobile',
                    views: {
                        '': {
                            templateUrl: 'tpl/layout_mobile.html'
                        },
                        'footer': {
                            templateUrl: 'tpl/layout_footer_mobile.html'
                        }
                    }
                })
                .state('layout.app', {
                    url: '/app',
                    views: {
                        '': {
                            templateUrl: 'tpl/layout_app.html'
                        },
                        'footer': {
                            templateUrl: 'tpl/layout_footer_fullwidth.html'
                        }
                    },
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load(['js/controllers/tab.js']);
                            }]
                    }
                })
                .state('apps', {
                    abstract: true,
                    url: '/apps',
                    templateUrl: 'tpl/layout.html'
                })
                .state('apps.note', {
                    url: '/note',
                    templateUrl: 'tpl/apps_note.html',
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load(['js/app/note/note.js',
                                    'vendor/libs/moment.min.js']);
                            }]
                    }
                })
                .state('apps.contact', {
                    url: '/contact',
                    templateUrl: 'tpl/apps_contact.html',
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load(['js/app/contact/contact.js']);
                            }]
                    }
                })
                .state('app.weather', {
                    url: '/weather',
                    templateUrl: 'tpl/apps_weather.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(
                                    {
                                        name: 'angular-skycons',
                                        files: ['js/app/weather/skycons.js',
                                            'vendor/libs/moment.min.js',
                                            'js/app/weather/angular-skycons.js',
                                            'js/app/weather/ctrl.js']
                                    }
                                );
                            }]
                    }
                })
                .state('music', {
                    url: '/music',
                    templateUrl: 'tpl/music.html',
                    controller: 'MusicCtrl',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load([
                                    'com.2fdevs.videogular',
                                    'com.2fdevs.videogular.plugins.controls',
                                    'com.2fdevs.videogular.plugins.overlayplay',
                                    'com.2fdevs.videogular.plugins.poster',
                                    'com.2fdevs.videogular.plugins.buffering',
                                    'js/app/music/ctrl.js',
                                    'js/app/music/theme.css'
                                ]);
                            }]
                    }
                })
                .state('music.home', {
                    url: '/home',
                    templateUrl: 'tpl/music.home.html'
                })
                .state('music.genres', {
                    url: '/genres',
                    templateUrl: 'tpl/music.genres.html'
                })
                .state('music.detail', {
                    url: '/detail',
                    templateUrl: 'tpl/music.detail.html'
                })
                .state('music.mtv', {
                    url: '/mtv',
                    templateUrl: 'tpl/music.mtv.html'
                })
                .state('music.mtvdetail', {
                    url: '/mtvdetail',
                    templateUrl: 'tpl/music.mtv.detail.html'
                })
                .state('music.playlist', {
                    url: '/playlist/{fold}',
                    templateUrl: 'tpl/music.playlist.html'
                })

                /////////////// HAVE USED/////////////////
                .state('access.signin', {
                    url: '/signin',
                    templateUrl: 'tpl/page_signin.html',
                    siteUrl: 'auth/login',
                    logoutUrl: 'auth/logout',
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load(['js/controllers/signin.js']);
                            }]
                    }
                })
                .state('app.main', {
                    url: '/main',
                    templateUrl: 'tpl/app_main.html',
                    siteUrl: 'main/index',
                    resolve: {
                        deps: ['uiLoad',
                            function (uiLoad) {
                                return uiLoad.load(['js/controllers/main.js']);
                            }]
                    }
                })
                /////////////CMS///////////////
                .state('app.cms_post_create', {
                    url: '/cms/post/create',
                    templateUrl: 'tpl/cms/post_create.html',
                    siteUrl: 'cms/post/create/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/cms/post.js']).then(
                                    function () {
                                        return $ocLazyLoad.load('textAngular');
                                    }
                                );
                            }]
                    }
                })
                .state('app.cms_post_index', {
                    url: '/cms/post/index',
                    templateUrl: 'tpl/cms/post_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/cms/lists.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.cms_post_update', {
                    url: '/cms/post/update/:id',
                    templateUrl: 'tpl/cms/post_create.html',
                    siteUrl: 'cms/post/update/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/cms/post.js']).then(
                                    function () {
                                        return $ocLazyLoad.load('textAngular');
                                    }
                                );
                            }]
                    }
                })
                //-------caregory---------//
                .state('app.cms_category_index', {
                    url: '/cms/category/index',
                    templateUrl: 'tpl/cms/category_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/cms/clists.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.cms_category_create', {
                    url: '/cms/category/create',
                    templateUrl: 'tpl/cms/category_create.html',
                    siteUrl: 'cms/category/create/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/cms/post.js']).then(
                                    function () {
                                        return $ocLazyLoad.load('textAngular');
                                    }
                                );
                            }]
                    }
                })

                .state('app.cms_category_update', {
                    url: '/cms/category/update/:id',
                    templateUrl: 'tpl/cms/category_create.html',
                    siteUrl: 'cms/category/update/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/cms/post.js']).then(
                                    function () {
                                        return $ocLazyLoad.load('textAngular');
                                    }
                                );
                            }]
                    }
                })
                ///////////////CMS END//////////////////
                .state('app.member_members_index', {
                    url: '/member/members/index',
                    templateUrl: 'tpl/member/member_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/member/member.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.member_members_blacklist', {
                    url: '/member/members/blacklist',
                    templateUrl: 'tpl/member/member_blacklist.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/member/blacklist.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.member_members_view', {
                    url: '/member/members/view/:id',
                    templateUrl: 'tpl/member/member_view.html',
                    siteUrl: 'member/members/view/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/member/view.js']).then(
                                    function () {
                                        return $ocLazyLoad.load('textAngular');
                                    }
                                );
                            }]
                    }
                })
                ///////////////member END//////////////////
                .state('app.member_authenticate_idcard', {
                    url: '/member/authenticate/idcard',
                    templateUrl: 'tpl/authenticate/idcard_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/authenticate/idcard.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.member_authenticate_email', {
                    url: '/member/authenticate/email',
                    templateUrl: 'tpl/authenticate/email.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/authenticate/email.js');
                                    }
                                );
                            }]
                    }
                })
                ///////////////authenticate END//////////////////
                .state('app.member_verification_phone', {
                    url: '/member/verification/phone',
                    templateUrl: 'tpl/verification/phone.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/verification/phone.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.member_verification_email', {
                    url: '/member/verification/email',
                    templateUrl: 'tpl/verification/email.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/verification/vemail.js');
                                    }
                                );
                            }]
                    }
                })
                ///////////////verification END//////////////////
                .state('app.asset_finance_index', {
                    url: '/asset/finance/index',
                    templateUrl: 'tpl/asset/finance_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/finance/finance_index.js');
                                    }
                                );
                            }]
                    }
                })
                ///////////////finance END//////////////////
                .state('app.asset_recharge_index', {
                    url: '/asset/recharge/index',
                    templateUrl: 'tpl/asset/recharge_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/asset/recharge_index.js');
                                    }
                                );
                            }]
                    }
                })
                ///////////////recharge END//////////////////
                .state('app.asset_withdraw_index', {
                    url: '/asset/withdraw/index',
                    templateUrl: 'tpl/asset/withdraw_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/asset/withdraw_index.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.asset_withdraw_first-trial', {
                    url: '/asset/withdraw/first-trial',
                    templateUrl: 'tpl/asset/withdraw_first-trial.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/asset/withdraw_first-trial.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.asset_withdraw_final-trial', {
                    url: '/asset/withdraw/final-trial',
                    templateUrl: 'tpl/asset/withdraw_final-trial.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/asset/withdraw_final-trial.js');
                                    }
                                );
                            }]
                    }
                })
                ///////////////withdraw END//////////////////
                .state('app.asset_experience_index', {
                    url: '/asset/experience/index',
                    templateUrl: 'tpl/asset/experience_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/asset/experience_index.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.asset_experience_setting', {
                    url: '/asset/experience/setting',
                    templateUrl: 'tpl/asset/experience_setting.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/asset/experience_setting.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.asset_experience_new-em', {
                    url: '/asset/experience/new-em',
                    templateUrl: 'tpl/asset/experience_new-em.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/asset/experience_new-em.js');
                                    }
                                );
                            }]
                    }
                })
                ///////////////experience END//////////////////
                .state('app.invest_product_create', {
                    url: '/invest/product/create',
                    templateUrl: 'tpl/invest/product_create.html',
                    siteUrl: 'invest/product/create/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('angularFileUpload').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/invest/product_create.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.invest_product_index', {
                    url: '/invest/product/index',
                    templateUrl: 'tpl/invest/product_index.html',
                    siteUrl: 'invest/product/index/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/invest/product_index.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.invest_product_update', {
                    url: '/invest/product/update/:id',
                    templateUrl: 'tpl/invest/product_create.html',
                    siteUrl: 'invest/product/update/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/invest/product_create.js']).then(
                                    function () {
                                        return $ocLazyLoad.load('angularFileUpload');
                                    }
                                );
                            }]
                    }
                })
                ///////////////invest END//////////////////
                .state('app.invest_activity_create', {
                    url: '/invest/activity/create',
                    templateUrl: 'tpl/invest/activity_create.html',
                    siteUrl: 'invest/activity/create/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/invest/activity_create.js']).then(
                                    function () {
                                        return $ocLazyLoad.load('textAngular');
                                    }
                                );
                            }]
                    }
                })
                .state('app.invest_activity_index', {
                    url: '/invest/activity/index',
                    templateUrl: 'tpl/invest/activity_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/invest/activity_index.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.invest_activity_update', {
                    url: '/invest/activity/update/:id',
                    templateUrl: 'tpl/invest/activity_create.html',
                    siteUrl: 'invest/activity/update/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/invest/activity_create.js']).then(
                                    function () {
                                        return $ocLazyLoad.load('textAngular');
                                    }
                                );
                            }]
                    }
                })
                ///////////////activity END//////////////////
                .state('app.invest_list_index', {
                    url: '/invest/list/index',
                    templateUrl: 'tpl/invest/list_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/invest/list_index.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.invest_list_view', {
                    url: '/invest/list/view/:id',
                    templateUrl: 'tpl/invest/list_view.html',
                    siteUrl: 'invest/list/view/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load(['js/controllers/invest/list_view.js']).then(
                                    function () {
                                        return $ocLazyLoad.load('textAngular');
                                    }
                                );
                            }]
                    }
                })
                .state('app.invest_list_month-index', {
                    url: '/invest/list/month-index',
                    templateUrl: 'tpl/invest/month-index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/invest/month-index.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.invest_list_tiyan-index', {
                    url: '/invest/listtiyan-index',
                    templateUrl: 'tpl/invest/tiyan-index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/invest/tiyan-index.js');
                                    }
                                );
                            }]
                    }
                })
                ///////////////invest END//////////////////
                .state('app.user_rbac_roles', {
                    url: '/user/rbac/roles',
                    templateUrl: 'tpl/user/roles_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/user/roles_index.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.user_rbac_role-create', {
                    url: '/user/rbac/role-create',
                    templateUrl: 'tpl/user/role_create.html',
                    siteUrl: 'user/rbac/role-create/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/user/role-create.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.user_rbac_role-view', {
                    url: '/user/rbac/role-view/:role_name',
                    templateUrl: 'tpl/user/role-view.html',
                    siteUrl: 'user/rbac/role-setting/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/user/role-create.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.user_management_index', {
                    url: '/user/management/index',
                    templateUrl: 'tpl/user/management_index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/user/managements_index.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.user_management_create', {
                    url: '/user/management/create',
                    templateUrl: 'tpl/user/management_create.html',
                    siteUrl: 'user/management/create/',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/user/management_view.js');
                                    }
                                );
                            }]
                    }
                })
                .state('app.user_management_view', {
                    url: '/user/management/view/:id',
                    templateUrl: 'tpl/user/management_view.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function ($ocLazyLoad) {
                                return $ocLazyLoad.load('ngGrid').then(
                                    function () {
                                        return $ocLazyLoad.load('js/controllers/user/management_view.js');
                                    }
                                );
                            }]
                    }
                })
        }
    ]
);