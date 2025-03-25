function getDetailSalesOrders() {
    // API endpoint configuration
    const API_BASE_URL = "https://superadmin.sagansa.id"; // Update this with your actual API base URL
    const API_ENDPOINT = "/api/detail-sales-orders";

    // Spreadsheet configuration
    const SHEET_NAME = "Detail Sales Orders";
    const HEADERS = [
        "ID",
        "Product",
        "Sales Order ID",
        "Delivery Date",
        "Quantity",
        "Unit Price",
        "Subtotal",
        "For",
        "Payment Status",
        "Delivery Status",
        "Store",
        "Order By",
        "Created At",
        "Updated At",
    ];

    try {
        // Make API request
        const response = UrlFetchApp.fetch(API_BASE_URL + API_ENDPOINT, {
            method: "get",
            headers: {
                Accept: "application/json",
                Authorization:
                    "Bearer " +
                    "1928|DvkiyXPhc5ixN0kx71TU6dai9jxaK0kIqvh5ggyJ81f4bc25",
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
        if (!jsonResponse.success) {
            throw new Error("API returned error status");
        }

        const data = jsonResponse.data;

        const ss = SpreadsheetApp.getActiveSpreadsheet();
        let sheet = ss.getSheetByName(SHEET_NAME);
        if (!sheet) {
            sheet = ss.insertSheet(SHEET_NAME);
        }

        // Clear only the API data columns (1-14)
        const dataRange = sheet.getRange(
            1,
            1,
            sheet.getLastRow(),
            HEADERS.length
        );
        dataRange.clearContent();

        // Set headers for API data columns
        sheet.getRange(1, 1, 1, HEADERS.length).setValues([HEADERS]);
        sheet.getRange(1, 1, 1, HEADERS.length).setFontWeight("bold");

        // Process data only if it exists and has items
        if (data && Array.isArray(data) && data.length > 0) {
            const spreadsheetData = data.map((item) => [
                item.id || "",
                item.product || "",
                item.sales_order_id || "",
                item.delivery_date || "",
                parseFloat(item.quantity) || 0,
                parseFloat(item.unit_price) || 0,
                parseFloat(item.subtotal_price) || 0,
                item.for || "",
                item.payment_status || "",
                item.delivery_status || "",
                item.store || "N/A",
                item.order_by || "",
                item.created_at || "",
                item.updated_at || "",
            ]);

            // Only proceed with formatting if we have data to process
            if (spreadsheetData.length > 0) {
                sheet
                    .getRange(2, 1, spreadsheetData.length, HEADERS.length)
                    .setValues(spreadsheetData);
                sheet.autoResizeColumns(1, HEADERS.length);

                // Format date columns only if we have data
                const dateColumns = [4, 13, 14];
                dateColumns.forEach((col) => {
                    sheet
                        .getRange(2, col, spreadsheetData.length, 1)
                        .setNumberFormat("yyyy-mm-dd hh:mm:ss");
                });

                // Format price columns as numbers
                const priceColumns = [6, 7]; // Unit Price and Subtotal columns
                priceColumns.forEach((col) => {
                    sheet
                        .getRange(2, col, spreadsheetData.length, 1)
                        .setNumberFormat("#,##0.00");
                });
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
        .addItem("Sync Detail Sales Orders", "getDetailSalesOrders")
        .addToUi();
}

function getApiToken() {
    const scriptProperties = PropertiesService.getScriptProperties();
    return scriptProperties.getProperty("API_TOKEN");
}
