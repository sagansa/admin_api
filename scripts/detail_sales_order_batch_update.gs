// Configuration
const API_BASE_URL = "https://superadmin.sagansa.id";
const API_ENDPOINT = "/api/detail-sales-orders";
const SHEET_NAME = "Detail Sales Orders";

// Column headers for the spreadsheet
const HEADERS = [
    "ID",
    "Product",
    "Sales Order ID",
    "Quantity",
    "Unit Price",
    "Subtotal",
    "Discount",
    "Created At",
    "Updated At",
];

/**
 * Gets the API token from script properties
 */
function getApiToken() {
    const scriptProperties = PropertiesService.getScriptProperties();
    return scriptProperties.getProperty("API_TOKEN");
}

/**
 * Validates the data before sending to API
 */
function validateData(data) {
    if (!data || !Array.isArray(data)) {
        throw new Error("Invalid data format");
    }

    data.forEach((row, index) => {
        if (!row.id) {
            throw new Error(`Missing ID at row ${index + 2}`);
        }
        if (isNaN(row.quantity) || row.quantity <= 0) {
            throw new Error(`Invalid quantity at row ${index + 2}`);
        }
        if (isNaN(row.unit_price) || row.unit_price < 0) {
            throw new Error(`Invalid unit price at row ${index + 2}`);
        }
        if (isNaN(row.discount)) {
            throw new Error(`Invalid discount at row ${index + 2}`);
        }
    });

    return true;
}

/**
 * Prepares the data from spreadsheet for API update
 */
function prepareDataForUpdate() {
    const sheet =
        SpreadsheetApp.getActiveSpreadsheet().getSheetByName(SHEET_NAME);
    if (!sheet) {
        throw new Error(`Sheet '${SHEET_NAME}' not found`);
    }

    const dataRange = sheet.getDataRange();
    const values = dataRange.getValues();
    const headers = values[0];

    // Create a map of header names to column indices
    const headerIndices = {};
    headers.forEach((header, index) => {
        headerIndices[header] = index;
    });

    // Extract data rows (excluding header row)
    const dataRows = values.slice(1);

    // Transform data into objects
    return dataRows.map((row) => ({
        id: row[headerIndices["ID"]],
        quantity: parseFloat(row[headerIndices["Quantity"]]),
        unit_price: parseFloat(row[headerIndices["Unit Price"]]),
        discount: parseFloat(row[headerIndices["Discount"]]) || 0,
    }));
}

/**
 * Sends batch update request to API
 */
function sendBatchUpdate(data) {
    const options = {
        method: "put",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: "Bearer " + getApiToken(),
        },
        payload: JSON.stringify({ updates: data }),
        muteHttpExceptions: true,
    };

    try {
        const response = UrlFetchApp.fetch(
            `${API_BASE_URL}${API_ENDPOINT}/batch`,
            options
        );
        const responseCode = response.getResponseCode();

        if (responseCode !== 200) {
            throw new Error(`API request failed with status ${responseCode}`);
        }

        const jsonResponse = JSON.parse(response.getContentText());
        if (!jsonResponse.success) {
            throw new Error("API returned error status");
        }

        return jsonResponse;
    } catch (error) {
        Logger.log("Error sending batch update: " + error.toString());
        throw error;
    }
}

/**
 * Main function to handle batch updates
 */
function batchUpdate() {
    try {
        // Prepare data from spreadsheet
        const data = prepareDataForUpdate();

        // Validate data
        validateData(data);

        // Send batch update request
        const response = sendBatchUpdate(data);

        // Log success
        Logger.log("Batch update completed successfully");
        SpreadsheetApp.getActiveSpreadsheet().toast(
            "Batch update completed successfully"
        );

        return response;
    } catch (error) {
        Logger.log("Error in batch update: " + error.toString());
        SpreadsheetApp.getActiveSpreadsheet().toast(
            `Error: ${error.toString()}`,
            "Batch Update Error",
            30
        );
        throw error;
    }
}

/**
 * Creates a menu item for batch updating
 */
function onOpen() {
    const ui = SpreadsheetApp.getUi();
    ui.createMenu("Detail Sales Order")
        .addItem("Batch Update", "batchUpdate")
        .addToUi();
}
