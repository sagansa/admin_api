const API_BASE_URL = 'https://superadmin.sagansa.id';
const API_ENDPOINT = '/api/detail-stock-cards';
const API_TOKEN = '1928|DvkiyXPhc5ixN0kx71TU6dai9jxaK0kIqvh5ggyJ81f4bc25';

function fetchDetailStockCards(stockCardId = null) {
  const url = stockCardId ? `${API_BASE_URL}${API_ENDPOINT}?stock_card_id=${stockCardId}` : `${API_BASE_URL}${API_ENDPOINT}`;

  const options = {
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${API_TOKEN}`,
      'Accept': 'application/json'
    },
    muteHttpExceptions: true
  };

  try {
    const response = UrlFetchApp.fetch(url, options);
    const responseCode = response.getResponseCode();

    if (responseCode !== 200) {
      const errorMessage = response.getContentText();
      Logger.log(`API request failed with status code: ${responseCode}`);
      Logger.log(`Error response: ${errorMessage}`);
      throw new Error(`Failed to fetch data. HTTP Status: ${responseCode}`);
    }

    const jsonResponse = JSON.parse(response.getContentText());
    if (!jsonResponse.success) {
      throw new Error("API returned error status");
    }

    return jsonResponse.data;
  } catch (error) {
    Logger.log(`Error: ${error.toString()}`);
    return null;
  }
}

function updateSpreadsheet() {
  const sheet = SpreadsheetApp.getActiveSheet();

  // Clear existing content
  sheet.clear();

  // Set headers
  const headers = [
    'ID',
    'Product ID',
    'Product Name',
    'Stock Card ID',
    'Stock Card Reference',
    'Quantity',
    'Description',
    'Created At',
    'Updated At'
  ];

  sheet.getRange(1, 1, 1, headers.length).setValues([headers]);
  sheet.getRange(1, 1, 1, headers.length).setFontWeight('bold');

  // Fetch data
  const data = fetchDetailStockCards();

  if (!data || data.length === 0) {
    sheet.getRange(2, 1).setValue('No data available');
    return;
  }

  // Prepare data for spreadsheet
  const spreadsheetData = data.map(item => [
    item.id,
    item.product_id,
    item.product ? item.product.name : 'N/A',
    item.stock_card_id,
    item.stock_card ? item.stock_card.reference : 'N/A',
    item.quantity,
    item.description || 'N/A',
    item.created_at,
    item.updated_at
  ]);

  // Write data to spreadsheet
  sheet.getRange(2, 1, spreadsheetData.length, headers.length)
    .setValues(spreadsheetData);

  // Auto-resize columns
  sheet.autoResizeColumns(1, headers.length);

  // Add filters
  sheet.getRange(1, 1, spreadsheetData.length + 1, headers.length)
    .createFilter();
}

function onOpen() {
  const ui = SpreadsheetApp.getUi();
  ui.createMenu('Stock Card Details')
    .addItem('Refresh Data', 'updateSpreadsheet')
    .addToUi();
}