# Yii2 to Yii3 Migration Audit

Date: 2026-04-19

## Current reality

- This repository is still a Yii2 Basic Application template (`yiisoft/yii2-app-basic`).
- The official Yii3 guide says there is no easy upgrade path from Yii2 and recommends treating Yii3 as a new application structure rather than an in-place framework swap.
- PHP is already suitable for Yii3: local CLI is `PHP 8.3.30`, while the current Yii3 app template requires PHP 8.2+.

Official references used for this assessment:

- Yii3 upgrade guide: https://yiisoft.github.io/docs/guide/intro/upgrade-from-v2.html
- Yii3 app template: https://github.com/yiisoft/app
- Yii3 database setup guide: https://yiisoft.github.io/docs/guide/start/databases.html

## Inventory from the legacy app

- Controllers: 32
- Models: 38
- View PHP files: 90
- `Yii::$app` usages found in app code: 587
- Yii2 widget-heavy view usages (`GridView`, `ActiveForm`, `DetailView`, `kartik`): 88
- Conflicted source files that should be resolved before or during porting: 17

Legacy Yii2-specific dependencies currently in use:

- `yiisoft/yii2-bootstrap`
- `yiisoft/yii2-swiftmailer`
- `kartik-v/yii2-widget-select2`
- `yii2mod/yii2-tree`
- `kartik-v/yii2-tree-manager`
- `kartik-v/yii2-grid`
- `kartik-v/yii2-date-range`
- `kartik-v/yii2-detail-view`
- `yiisoft/yii2-mongodb`
- `mongodb/mongodb`

## What was prepared in this repo

- A fresh Yii3 application scaffold was created in `yii3-app/`.
- MySQL, Active Record, cache-file, and DB migration packages were added to the Yii3 app.
- `yii3-app` now has:
  - environment-based database configuration
  - a default MySQL connection registration
  - Active Record-compatible connection bootstrap
  - migration namespace registration for `App\Migration`

## Recommended migration strategy

### Phase 1: Stabilize legacy Yii2 app

- Remove or resolve the 17 conflicted source files.
- Upgrade the existing Yii2 app to the latest supported Yii2 release first.
- Replace direct `Yii::$app` access with injected dependencies where practical.
- Move reusable business logic out of controllers and ActiveRecord models into service classes.

### Phase 2: Port the domain into Yii3

- Start with database connection, repositories, and read-only screens.
- Port the public site first:
  - `SiteController`
  - news listing
  - login/request-password/reset-password flow
- After that, port authenticated modules one bounded area at a time:
  - dashboard
  - user/register
  - transaction/buy/transfer
  - withdrawal/point payment/point redeem

### Phase 3: Replace Yii2-only UI dependencies

- `kartik` grid/date-range/select2/detail widgets need redesign or replacement in Yii3 views.
- `yii2-swiftmailer` should be replaced with a Yii3-compatible mailer approach.
- `yii2-mongodb` usage needs a separate Yii3-compatible integration decision.

## Suggested first concrete porting targets

1. Public homepage and news pages.
2. Login and password reset flow.
3. `yr_user`, `yr_news`, and `yr_transaction` records as Yii3 models/repositories.
4. One authenticated dashboard route as proof of concept.

## Important note

The safest path is to keep the existing Yii2 app running while the new Yii3 app is built in parallel inside `yii3-app/`. After key modules are ported and verified, web traffic can be switched over gradually.
