// API endpoint configuration
const API_BASE_URL = "https://superadmin.sagansa.id";
const API_ENDPOINT = "/api/stock-cards/daily";
const API_TOKEN = "1928|DvkiyXPhc5ixN0kx71TU6dai9jxaK0kIqvh5ggyJ81f4bc25";

// Spreadsheet configuration
const SHEET_NAME = "Daily Stock Cards";
const HEADERS = [
    "ID",
    "Product ID",
    "Product Name",
    "Store Name",
    "Date",
    "Status",
    "Notes",
    "Created At",
    "Updated At",
];

function getDailyStockCards() {
    try {
        const response = UrlFetchApp.fetch(API_BASE_URL + API_ENDPOINT, {
            method: "get",
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${API_TOKEN}`,
            },
            muteHttpExceptions: true,
        });

        const responseCode = response.getResponseCode();
        if (responseCode !== 200) {
            throw new Error(
                "Failed to fetch data. HTTP Status: " + responseCode
            );
        }

        const jsonResponse = JSON.parse(response.getContentText());
        const data = Array.isArray(jsonResponse)
            ? jsonResponse
            : jsonResponse.data || [];

        const ss = SpreadsheetApp.getActiveSpreadsheet();
        let sheet = ss.getSheetByName(SHEET_NAME);
        if (!sheet) {
            sheet = ss.insertSheet(SHEET_NAME);
        }

        // Clear only the API data columns
        const dataRange = sheet.getRange(
            1,
            1,
            sheet.getLastRow(),
            HEADERS.length
        );
        dataRange.clearContent();

        // Set headers
        sheet.getRange(1, 1, 1, HEADERS.length).setValues([HEADERS]);
        sheet.getRange(1, 1, 1, HEADERS.length).setFontWeight("bold");

        // Process data only if it exists and has items
        if (data && Array.isArray(data) && data.length > 0) {
            const spreadsheetData = data.map((item) => [
                item.id || "",
                item.product_id || "",
                item.product_name || "N/A",
                item.store_name || "N/A",
                item.date || "N/A",
                item.status || "N/A",
                item.notes || "N/A",
                item.created_at || "",
                item.updated_at || "",
            ]);

            // Only proceed with formatting if we have data to process
            if (spreadsheetData.length > 0) {
                sheet
                    .getRange(2, 1, spreadsheetData.length, HEADERS.length)
                    .setValues(spreadsheetData);
                sheet.autoResizeColumns(1, HEADERS.length);

                // Format date columns
                const dateColumns = [5, 8, 9]; // Date, Created At, Updated At columns
                dateColumns.forEach((col) => {
                    sheet
                        .getRange(2, col, spreadsheetData.length, 1)
                        .setNumberFormat("yyyy-mm-dd hh:mm:ss");
                });

                // Add filters
                sheet
                    .getRange(1, 1, spreadsheetData.length + 1, HEADERS.length)
                    .createFilter();
            }
        } else {
            Logger.log("No data available to update the spreadsheet");
        }

        Logger.log("Data successfully updated");
    } catch (error) {
        Logger.log("Error: " + error.toString());
        throw error;
    }
}

function onOpen() {
    const ui = SpreadsheetApp.getUi();
    ui.createMenu("API Sync")
        .addItem("Sync Daily Stock Cards", "getDailyStockCards")
        .addToUi();
}

function getApiToken() {
    const scriptProperties = PropertiesService.getScriptProperties();
    return scriptProperties.getProperty("API_TOKEN");
}
