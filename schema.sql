-- 1. USER TABLE
-- Stores accounts and their system roles
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Keep this long for hashed passwords
    role VARCHAR(20) NOT NULL DEFAULT 'user'
);

-- 2. EVENT TABLE
-- Stores details for specific events
CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255), -- Stores the URL or file path to the image
    location VARCHAR(150) NOT NULL,
    date DATE NOT NULL
);

-- 3. BOOKING TABLE
-- Connects a user to an event (Many-to-Many relationship)
CREATE TABLE bookings (
    id SERIAL PRIMARY KEY,
    booking_date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    uid INT NOT NULL,
    e_id INT NOT NULL,
    
    -- Foreign Key Constraints
    -- ON DELETE CASCADE ensures if a user/event is deleted, their bookings are too
    CONSTRAINT fk_user FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_event FOREIGN KEY (e_id) REFERENCES events(id) ON DELETE CASCADE
);