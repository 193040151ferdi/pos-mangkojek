// Load config first to define DB constants used by the session handler
require_once __DIR__ . '/config/config.php';

// Register custom database session handler for Vercel
require_once __DIR__ . '/core/AppSessionHandler.php';
AppSessionHandler::register();

session_start();

require_once __DIR__ . '/core/App.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Database.php';

// Helper function for product images
require_once __DIR__ . '/helpers/image_helper.php';
