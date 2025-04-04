const https = require('https');
const fs = require('fs');
const path = require('path');
const { exec } = require('child_process');
const http = require('http');

// SSL configuration
const sslOptions = {
  key: fs.readFileSync(path.join(__dirname, 'storage/ssl/key.pem')),
  cert: fs.readFileSync(path.join(__dirname, 'storage/ssl/cert.pem'))
};

// Create HTTPS server
const server = https.createServer(sslOptions, (req, res) => {
  // Forward requests to Laravel's development server
  const options = {
    hostname: 'localhost',
    port: 8000,
    path: req.url,
    method: req.method,
    headers: req.headers
  };

  const proxyReq = http.request(options, (proxyRes) => {
    res.writeHead(proxyRes.statusCode, proxyRes.headers);
    proxyRes.pipe(res);
  });

  req.pipe(proxyReq);
});

// Start server
const PORT = process.env.PORT || 443;
server.listen(PORT, () => {
  console.log(`HTTPS Server running on port ${PORT}`);
  
  // Start Laravel's development server
  exec('php artisan serve', (error, stdout, stderr) => {
    if (error) {
      console.error(`Error starting Laravel server: ${error}`);
      return;
    }
    console.log(`Laravel server output: ${stdout}`);
  });
}); 