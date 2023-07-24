<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:param name="actionType" select="'all'" />
    <xsl:template match="inventoryActivityLogs">
        <html>
            <head>
                <title>Inventory Activity Logs</title>

                <style type="text/css">
                    /* Reset styles */
                    * {
                    box-sizing: border-box;
                    margin: 0;
                    padding: 0;
                    }

                    /* Global styles */
                    body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    line-height: 1.5;
                    color: #333;
                    margin: 0 20px;
                    }

                    h1 {
                    font-size: 24px;
                    margin-bottom: 20px;
                    }

                    table {
                    table-layout: fixed;
                    width: 100%;
                    max-width: 100%;
                    margin-top: 20px;
                    margin-bottom: 20px;
                    }

                    th, td {
                    padding: 10px;
                    text-align: left;
                    border: 1px solid #ddd;
                    max-width: 400px;
                    word-wrap: break-word;
                    }

                    th {
                    font-size: 14px;
                    }

                    th:nth-child(1),
                    td:nth-child(1) {
                    width: 50px;
                    }

                    th:nth-child(2),
                    td:nth-child(2) {
                    width: 25px;
                    }

                    th:nth-child(3),
                    td:nth-child(3) {
                    width: 145px;
                    }

                    th:nth-child(4),
                    td:nth-child(4) {
                    width: 145px;
                    }

                    th:nth-child(5),
                    td:nth-child(5) {
                    width: 35px;
                    }

                    th {
                    font-weight: bold;
                    }

                    tr:nth-child(even) {
                    background-color: #f2f2f2;
                    }

                    .action-button {
                    background-color: #4a83ef;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    margin-right: 10px;
                    }

                    /* Media queries */
                    @media screen and (max-width: 600px) {
                    /* Responsive table */
                    table {
                    font-size: 14px;
                    }

                    th, td {
                    padding: 5px;
                    }

                    .action-button {
                    display: block;
                    margin-bottom: 10px;
                    }
                    }
                </style>
            </head>
            <body>
                <h1>Inventory Activity Logs</h1>

                <div>
                    <form>
                        <button type="submit" name="actionType" value="all" class="action-button">Show
        All</button>
                        <button type="submit" name="actionType" value="be created"
                            class="action-button">Show Created</button>
                        <button type="submit" name="actionType" value="be updated"
                            class="action-button">Show Updated</button>
                        <button type="submit" name="actionType" value="be deleted"
                            class="action-button">Show Deleted</button>
                    </form>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Inventory Name</th>
                            <th>Action</th>
                            <th>Old Values</th>
                            <th>New Values</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>

                    <tbody>
                        <xsl:for-each select="inventoryActivityLog">
                            <xsl:choose>
                                <xsl:when test="$actionType = 'all' or @action = $actionType">
                                    <tr>
                                        <td>
                                            <xsl:value-of select="@inventory_name" />
                                        </td>
                                        <td>
                                            <xsl:value-of select="@action" />
                                        </td>
                                        <td>
                                            <xsl:value-of select="old_values" />
                                        </td>
                                        <td>
                                            <xsl:value-of select="new_values" />
                                        </td>
                                        <td>
                                            <xsl:value-of select="@created_at" />
                                        </td>
                                    </tr>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:if test="position() = 1">
                                        <tr>
                                            <td colspan="6">No records found for the selected action type.</td>
                                        </tr>
                                    </xsl:if>
                                </xsl:otherwise>
                            </xsl:choose>
                        </xsl:for-each>
                    </tbody>
                </table>
                <div style="text-align: center; margin-top: 20px;">
                    <button onclick="window.location.href='../inventory'"
                        style="background-color: #4a83ef; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
                        Back to Inventory
                    </button>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
