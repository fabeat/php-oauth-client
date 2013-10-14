CREATE TABLE IF NOT EXISTS states (
    user_id VARCHAR(255) NOT NULL,
    data TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS access_tokens (
    user_id VARCHAR(255) NOT NULL,
    data TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS refresh_tokens (
    user_id VARCHAR(255) NOT NULL,
    data TEXT NOT NULL
);
