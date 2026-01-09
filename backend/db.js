const mysql = require('mysql2/promise');

// Create connection pool
const pool = mysql.createPool({
  host: process.env.MYSQLHOST || 'mysql.railway.internal',
  user: process.env.MYSQLUSER || 'root',
  password: process.env.MYSQLPASSWORD || 'mmBFtdWaLhhewwlleRUTvDBWsawwJVRh',
  database: process.env.MYSQLDATABASE || 'railway',
  port: process.env.MYSQLPORT || 3306,
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// Test connection
async function testConnection() {
  try {
    const connection = await pool.getConnection();
    console.log('[DB] MySQL connection successful');
    connection.release();
    return true;
  } catch (error) {
    console.error('[DB] MySQL connection failed:', error.message);
    return false;
  }
}

// Initialize database tables
async function initializeTables() {
  try {
    const connection = await pool.getConnection();

    // Create event_registrations table
    await connection.execute(`
      CREATE TABLE IF NOT EXISTS event_registrations (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(64) UNIQUE NOT NULL,
        name VARCHAR(255),
        email VARCHAR(255),
        phone VARCHAR(50),
        member_type VARCHAR(100),
        plan VARCHAR(50),
        payment_amount DECIMAL(10,2),
        payment_currency VARCHAR(10),
        transaction_id VARCHAR(100),
        registration_status VARCHAR(50),
        qr_checked_in BOOLEAN DEFAULT FALSE,
        checked_in_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_session_id (session_id),
        INDEX idx_email (email),
        INDEX idx_qr_checked_in (qr_checked_in)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    `);

    console.log('[DB] Tables initialized successfully');
    connection.release();
  } catch (error) {
    console.error('[DB] Error initializing tables:', error);
    throw error;
  }
}

module.exports = {
  pool,
  testConnection,
  initializeTables
};