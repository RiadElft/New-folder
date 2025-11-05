# Render API Key Setup for MCP

## How to Get Your Render API Key

1. **Log in to Render Dashboard**
   - Go to [https://dashboard.render.com](https://dashboard.render.com)
   - Sign in to your account

2. **Navigate to Account Settings**
   - Click on your profile icon (top right)
   - Select **Account Settings**
   - Or go directly to: [https://dashboard.render.com/account](https://dashboard.render.com/account)

3. **Create API Key**
   - Scroll down to the **API Keys** section
   - Click **Create API Key** button
   - Give it a descriptive name (e.g., "MCP Integration" or "Cursor MCP")
   - Click **Create**
   - **⚠️ IMPORTANT:** Copy the API key immediately - you won't be able to see it again!

4. **Update MCP Configuration**
   - Open: `c:\Users\undra\.cursor\mcp.json`
   - Replace `<YOUR_API_KEY>` with your actual API key
   - Save the file

5. **Restart Cursor**
   - Close and reopen Cursor for the MCP changes to take effect

## Security Notes

- ⚠️ **Never commit your API key to git**
- ⚠️ **Keep your API key secret**
- ✅ The API key is already in `.gitignore` (if you have one)
- ✅ If your key is compromised, revoke it in Render dashboard and create a new one

## What the Render MCP Can Do

Once configured, the Render MCP server allows you to:
- Deploy services programmatically
- Manage databases
- Update environment variables
- View deployment logs
- Monitor service status
- And more!

## Alternative: Use Render API Directly

If the MCP server URL doesn't work, you can also use Render's REST API directly:
- API Base URL: `https://api.render.com`
- Documentation: [https://api-docs.render.com](https://api-docs.render.com)

## Troubleshooting

If the MCP server doesn't connect:
1. Verify your API key is correct
2. Check that the URL `https://mcp.render.com/mcp` is correct
3. Try using `https://api.render.com` instead
4. Check Render's status page for any outages
5. Make sure you've restarted Cursor after updating the config

