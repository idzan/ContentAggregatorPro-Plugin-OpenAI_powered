# Content Aggregator Pro

A WordPress plugin designed to aggregate, combine, and rewrite content from multiple RSS feeds using OpenAI and Google Gemini APIs.

## Features

- **RSS Feed Integration**: Add and manage multiple RSS feed sources.
- **Content Summarization**: Use OpenAI or Google Gemini APIs to summarize content.
- **Custom Categories**: Specify categories to rewrite for more focused aggregation.
- **Automated Post Creation**: Automatically create WordPress posts with rewritten content and featured images.
- **Source Attribution**: Append source details and featured image credits to posts.
- **Easy Integration**: Fully compatible with WordPress categories and cron jobs.
- **Tabbed Admin Interface**: Organized settings for API keys, feed management, and advanced cron options.

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- API keys for:
  - OpenAI (for content rewriting and summarization)
  - Google Gemini (optional, for content summarization)

## Installation

1. Download the plugin files.
2. Upload the plugin folder to the `wp-content/plugins/` directory on your WordPress installation.
3. Activate the plugin through the WordPress admin dashboard under **Plugins**.
4. Navigate to **Settings > Content Aggregator** to configure.

## Configuration

### Adding API Keys

1. Navigate to **Settings > Content Aggregator** in the WordPress admin.
2. Enter your OpenAI API key in the **API Keys** tab.
3. (Optional) Enter your Google Gemini API key in the **API Keys** tab.
4. Click **Save Settings**.

### Managing RSS Feeds and Categories

1. Go to the **Feeds & Categories** tab in the settings.
2. Add RSS feed URLs under the **RSS Feed Sources** section.
3. Specify categories to rewrite in the **Feed Categories to Rewrite** field.
   - Categories should match those from the RSS feed or source site.
   - Examples:
     - From RSS feed: `Technology, Business`
     - From source site: `Server Infrastructure, Cloud Computing`
4. Save your changes.

### Cron Job Configuration

1. Navigate to the **Cron Job (Advanced)** tab.
2. Select how often the plugin should check for new content:
   - **Hourly**
   - **Every 12 Hours**
   - **Every 24 Hours**
3. Save your settings.

## How Content Is Posted to Your Website

1. **Fetch Content**:
   - The plugin fetches posts from the specified RSS feeds.
   - Only posts matching the defined categories are processed.

2. **Combine and Rewrite Content**:
   - Relevant content from multiple sources is combined into a single post.
   - The plugin uses OpenAI or Google Gemini APIs to summarize and rewrite content.

3. **Create WordPress Posts**:
   - The processed content is published as a WordPress post.
   - Posts are assigned to their respective categories based on your configuration.

4. **Add Featured Images and Attribution**:
   - If the source includes a featured image, it is downloaded and added to your Media Library.
   - The plugin appends attribution details for the original sources and featured images at the end of the post.

5. **Automated Workflow**:
   - The plugin runs periodically based on the configured cron schedule.

## Notes

- Ensure API keys are valid to enable content summarization and rewriting.
- Feed URLs should be well-formed and accessible.
- Categories must match exactly as they appear in the RSS feeds.

## Credits

Developed with ❤️ by Marko

Special thanks to **ChatGPT by OpenAI** for assisting with development, design, and implementation.  
