export function renderHomePage(): string {
    return `
        <html>
            <head>
                <title>Home</title>
                <link rel="stylesheet" type="text/css" href="/css/style.css">
            </head>
            <body>
                <h1>Welcome to the Home Page</h1>
                <p>This is the main entry point of the application.</p>
            </body>
        </html>
    `;
}

export function renderAboutPage(): string {
    return `
        <html>
            <head>
                <title>About</title>
                <link rel="stylesheet" type="text/css" href="/css/style.css">
            </head>
            <body>
                <h1>About Us</h1>
                <p>This page provides information about our application.</p>
            </body>
        </html>
    `;
}

// Additional view rendering functions can be added here as needed.