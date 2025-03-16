export function setRoutes(app) {
    const IndexController = require('../controllers/index').IndexController;
    const indexController = new IndexController();

    app.get('/', indexController.home.bind(indexController));
    app.get('/about', indexController.about.bind(indexController));
    app.get('/contact', indexController.contact.bind(indexController));
}