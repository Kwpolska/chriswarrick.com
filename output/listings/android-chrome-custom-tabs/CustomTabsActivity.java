package com.chriswarrick.customtabslauncher;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;

import androidx.browser.customtabs.CustomTabsIntent;
import androidx.core.content.ContextCompat;

public class CustomTabsActivity extends Activity {
    public static String SITE_URL = "https://chriswarrick.com/";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);  // Layout is blank.
        CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
        builder.setToolbarColor(ContextCompat.getColor(this, R.color.colorPrimary));
        builder.setSecondaryToolbarColor(ContextCompat.getColor(this, R.color.colorPrimaryDark));
        builder.setShowTitle(true);
        builder.enableUrlBarHiding();
        CustomTabsIntent intent = builder.build();
        // Force browser to Chrome instead of system default.
        intent.intent.setPackage("com.android.chrome");
        intent.launchUrl(this, Uri.parse(SITE_URL));
        finish();
    }
}
