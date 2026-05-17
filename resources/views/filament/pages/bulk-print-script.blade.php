<div x-data x-on:open-bulk-urls.window="
    $event.detail.urls.forEach(url => window.open(url, '_blank'));
"></div>
