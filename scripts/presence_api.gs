// Configuration
const API_BASE_URL = "YOUR_API_BASE_URL"; // Replace with your actual API base URL

// Store authentication token
let authToken = null;

/**
 * Authenticates with the API using email and password
 */
function authenticate() {
    const email = "YOUR_EMAIL"; // Replace with actual email
    const password = "YOUR_PASSWORD"; // Replace with actual password

    const options = {
        method: "post",
        contentType: "application/json",
        payload: JSON.stringify({
            email: email,
            password: password,
        }),
    };

    try {
        const response = UrlFetchApp.fetch(API_BASE_URL + "/login", options);
        const jsonResponse = JSON.parse(response.getContentText());
        authToken = jsonResponse.token;
        return true;
    } catch (error) {
        Logger.log("Authentication failed: " + error.toString());
        return false;
    }
}

/**
 * Fetches presence data from the API
 */
function fetchPresenceData() {
    if (!authToken) {
        const isAuthenticated = authenticate();
        if (!isAuthenticated) {
            throw new Error("Failed to authenticate");
        }
    }

    const options = {
        method: "get",
        headers: {
            Authorization: "Bearer " + authToken,
        },
    };

    try {
        const response = UrlFetchApp.fetch(
            API_BASE_URL + "/presences",
            options
        );
        const jsonResponse = JSON.parse(response.getContentText());
        return jsonResponse.data;
    } catch (error) {
        Logger.log("Failed to fetch presence data: " + error.toString());
        throw error;
    }
}

/**
 * Updates the spreadsheet with presence data
 */
function updateSpreadsheet() {
    const sheet = SpreadsheetApp.getActiveSheet();

    try {
        const presenceData = fetchPresenceData();

        // Set headers
        const headers = [
            "Creator",
            "Store",
            "Shift Name",
            "Shift Start Time",
            "Shift End Time",
            "Shift Duration",
            "Check In",
            "Check Out",
            "Created At",
            "Updated At",
        ];
        sheet.getRange(1, 1, 1, headers.length).setValues([headers]);

        // Populate data
        const data = presenceData.map((presence) => [
            presence.creator,
            presence.store,
            presence.shift_name,
            presence.shift_start_time,
            presence.shift_end_time,
            presence.shift_duration,
            presence.check_in,
            presence.check_out,
            presence.created_at,
            presence.updated_at,
        ]);

        if (data.length > 0) {
            sheet.getRange(2, 1, data.length, headers.length).setValues(data);
        }

        // Format the sheet
        sheet.autoResizeColumns(1, headers.length);
    } catch (error) {
        Logger.log("Failed to update spreadsheet: " + error.toString());
        throw error;
    }
}

/**
 * Creates a menu item to refresh data
 */
function onOpen() {
    const ui = SpreadsheetApp.getUi();
    ui.createMenu("Presence API")
        .addItem("Refresh Data", "updateSpreadsheet")
        .addToUi();
}
