App = Ember.Application.create();
 
App.Router.map(function() {
    this.resource('home', {
        path: '/'
    });
    this.resource('about');
    this.resource('contact');
});