class IndexController {
    // Method to handle the request for the home page
    public async getHome(req, res) {
        // Logic to render the home page
        res.send("Welcome to the home page!");
    }

    // Method to handle the request for a specific resource
    public async getResource(req, res) {
        const resourceId = req.params.id;
        // Logic to fetch and return the resource
        res.send(`Resource with ID: ${resourceId}`);
    }

    // Additional methods can be added here for other routes
}

export default IndexController;