#!/bin/bash

echo "🚀 Scheduler started..."

while [ true ]
do
    echo "📅 Starting schedule run at $(date '+%Y-%m-%d %H:%M:%S')..."
    php artisan schedule:run --no-interaction
    echo "😴 Finished schedule run, sleeping for 60 seconds..."
    sleep 60
done