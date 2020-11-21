If you are using macOS to work on czqo-core, you may run into issues with the GD Library, causes the following error:  

```
Call to undefined function Intervention\Image\Gd\imagettfbbox() 
```

This is because the PHP copy installed by default on macOS does not have the GD library enabled.

### Fix:

1. Install MAMP and set the PHP version to 7.3.
2. Install Freetype with Homebrew (`brew install freetype`)
3. Set the PHP alias in macOS to MAMP in Terminal with the following command: `alias php=/Applications/MAMP/bin/php/php7.3.XX/bin/php`. Your path may be different.
4. Reload the Laravel development server.
