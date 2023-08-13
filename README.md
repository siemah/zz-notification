# Push Notification Expo

This Expo Plugin for WordPress is designed to simplify the integration of push notifications to all Android and iOS devices. With ease of use in mind.

## Instructions

You may use composer to make it easy and install the dependencies within the `/libs` folder as following:

```shell
composer install --ignore-platform-reqs
```

then zip your plugin and upload it to your woordpress instance or just use the `.zip` file directly.

If you want to target your platform just remove the following from `composer.json`:

```json
"config": {
    "platform-check": false
}
```

## Credits

We want to salut [ctwillie](https://github.com/ctwillie) for his package [ctwillie/expo-server-sdk-php](https://github.com/ctwillie/expo-server-sdk-php/).