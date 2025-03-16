import { IndexController } from '../src/controllers/index';
import { Model } from '../src/models/index';
import { Service } from '../src/services/index';

describe('IndexController', () => {
    let controller: IndexController;
    let model: Model;
    let service: Service;

    beforeEach(() => {
        model = new Model();
        service = new Service(model);
        controller = new IndexController(service);
    });

    it('should return the correct view for the home route', () => {
        const response = controller.home();
        expect(response).toContain('<h1>Welcome to the Home Page</h1>');
    });

    // Add more tests for other methods in IndexController
});