<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:param name="actionType" select="'all'" />

    <xsl:template match="userAuditLogs">
        <html>
            <head>
                <title>User Audit Logs</title>
                <style type="text/css">
                    table {
                    border-collapse: collapse;
                    width: 100%;
                    }
                    th, td {
                    text-align: left;
                    padding: 8px;
                    border-bottom: 1px solid #ddd;
                    }
                    th {
                    background-color: #f2f2f2;
                    }
                    tr:hover {
                    background-color: #f5f5f5;
                    }
                    .action-button {
                    background-color: #4CAF50;
                    border: none;
                    color: white;
                    padding: 10px 20px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 4px 2px;
                    cursor: pointer;
                    border-radius: 4px;
                    }
                </style>
            </head>
            <body>
                <h1>User Audit Logs</h1>
                <div>
                    <form>
                        <button type="submit" name="actionType" value="all" 
                            class="action-button">ShowAll</button>
                        <button type="submit" name="actionType" value="created"
                            class="action-button">Show Created</button>
                        <button type="submit" name="actionType" value="updated"
                            class="action-button">Show Updated</button>
                        <button type="submit" name="actionType" value="deleted"
                            class="action-button">Show Deleted</button>
                    </form>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Action</th>
                            <th>Old Values</th>
                            <th>New Values</th>
                            <th>User Agent</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>

                        <xsl:for-each select="userAuditLog">
                            <xsl:choose>
                                <xsl:when test="$actionType = 'all' or @action = $actionType">
                                    <tr>
                                        <td>
                                            <xsl:value-of select="@id" />
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
                                            <xsl:value-of select="@user_agent" />
                                        </td>
                                        <td>
                                            <xsl:value-of select="@created_at" />
                                        </td>
                                    </tr>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:if test="position() = 1">
                                        <tr>
                                            <td colspan="6">No records found for the selected action
        type.</td>
                                        </tr>
                                    </xsl:if>
                                </xsl:otherwise>
                            </xsl:choose>
                        </xsl:for-each>
                    </tbody>
                </table>
                <div style="text-align: center; margin-top: 20px;">
                    <button onclick="window.location.href='adminPanel'"
                        style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
        Back to Admin Page
                    </button>
                </div>
            </body>
        </html>
    </xsl:template>


</xsl:stylesheet>