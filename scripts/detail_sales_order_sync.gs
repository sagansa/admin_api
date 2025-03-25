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
 * Fetches detail sales order data from the API
 */
function fetchDetailSalesOrders() {
    const options = {
        method: "get",
        headers: {
            Accept: "application/json",
            Authorization: "Bearer " + getApiToken(),
        },
        muteHttpExceptions: true,
    };

    try {
        const response = UrlFetchApp.fetch(
            API_BASE_URL + API_ENDPOINT,
            options
        );
        const responseCode = response.getResponseCode();

        if (responseCode !== 200) {
            throw new Error(
                "Failed to fetch data. HTTP Status: " + responseCode
            );
        }

        const jsonResponse = JSON.parse(response.getContentText());
        if (!jsonResponse.success) {
            throw new Error("API returned error status");
        }

        return jsonResponse.data;
    } catch (error) {
        Logger.log("Error fetching data: " + error.toString());
        throw error;
    }
}

/**
 * Updates the spreadsheet with detail sales order data
 */
function updateSpreadsheet() {
    const sheet =
        SpreadsheetApp.getActiveSpreadsheet().getSheetByName(SHEET_NAME) ||
        SpreadsheetApp.getActiveSpreadsheet().insertSheet(SHEET_NAME);

    try {
        const data = fetchDetailSalesOrders();

        // Clear existing content
        sheet.clear();

        // Set headers
        sheet.getRange(1, 1, 1, HEADERS.length).setValues([HEADERS]);
        sheet.getRange(1, 1, 1, HEADERS.length).setFontWeight("bold");

        if (data && data.length > 0) {
            const spreadsheetData = data.map((item) => [
                item.id,
                item.product.name,
                item.sales_order_id,
                item.quantity,
                item.unit_price,
                item.subtotal_price,
                item.discount || 0,
                item.created_at,
                item.updated_at,
            ]);

            sheet
                .getRange(2, 1, spreadsheetData.length, HEADERS.length)
                .setValues(spreadsheetData);

            // Format date columns
            const dateColumns = [8, 9]; // Created At, Updated At columns
            dateColumns.forEach((col) => {
                sheet
                    .getRange(2, col, spreadsheetData.length, 1)
                    .setNumberFormat("yyyy-mm-dd hh:mm:ss");
            });

            // Format number columns
            const numberColumns = [4, 5, 6, 7]; // Quantity, Unit Price, Subtotal, Discount columns
            numberColumns.forEach((col) => {
                sheet
                    .getRange(2, col, spreadsheetData.length, 1)
                    .setNumberFormat("#,##0.00");
            });

            // Auto-resize columns
            sheet.autoResizeColumns(1, HEADERS.length);
        }

        Logger.log("Spreadsheet updated successfully");
    } catch (error) {
        Logger.log("Error updating spreadsheet: " + error.toString());
        throw error;
    }
}

/**
 * Creates a menu item for syncing data
 */
function onOpen() {
    const ui = SpreadsheetApp.getUi();
    ui.createMenu("Detail Sales Order")
        .addItem("Sync Data", "updateSpreadsheet")
        .addToUi();
}

/**
 * Gets the API token from script properties
 */
function getApiToken() {
    const scriptProperties = PropertiesService.getScriptProperties();
    return scriptProperties.getProperty("API_TOKEN");
}
