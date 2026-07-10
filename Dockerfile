FROM ghcr.io/unb-libraries/drupal:11.x-1.x-unblib

# Install additional OS packages.
ENV ADDITIONAL_OS_PACKAGES="postfix php${PHP_VERSION}-ldap php${PHP_VERSION}-xmlreader php${PHP_VERSION}-zip php${PHP_VERSION}-pecl-redis"
ENV DRUPAL_SITE_ID="voi"
ENV DRUPAL_SITE_URI="voi.lib.unb.ca"
ENV DRUPAL_SITE_UUID="87375ebe-f3d5-4681-b0ca-5e1352a52f83"

# Build application.
COPY ./build/ /build/
RUN ${RSYNC_MOVE} /build/scripts/container/ /scripts/ && \
  /scripts/addOsPackages.sh && \
  /scripts/initOpenLdap.sh && \
  /scripts/setupStandardConf.sh && \
  /scripts/build.sh

# Deploy configuration.
COPY ./configuration ${DRUPAL_CONFIGURATION_DIR}
RUN /scripts/pre-init.d/72_secure_config_sync_dir.sh

# Deploy custom modules, themes.
COPY ./custom/themes ${DRUPAL_ROOT}/themes/custom
COPY ./custom/modules ${DRUPAL_ROOT}/modules/custom

COPY ./custom/splash  ${DRUPAL_ROOT}/splash
COPY ./custom/google5ed9cf1363065b07.html ${DRUPAL_ROOT}/google5ed9cf1363065b07.html

# Container metadata.
ARG BUILD_DATE
ARG VCS_REF
ARG VERSION
LABEL org.opencontainers.image.title="voi.lib.unb.ca" \
  org.opencontainers.image.description="voi.lib.unb.ca is the digital home for the bilingual research project, 'Vocabularies of Identity II'" \
  org.opencontainers.image.vendor="University of New Brunswick Libraries" \
  org.opencontainers.image.authors="UNB Libraries <libsupport@unb.ca>" \
  org.opencontainers.image.url="https://voi.lib.unb.ca" \
  org.opencontainers.image.source="https://github.com/unb-libraries/voi.lib.unb.ca" \
  org.opencontainers.image.version="$VERSION" \
  org.opencontainers.image.revision="$VCS_REF" \
  org.opencontainers.image.created="$BUILD_DATE" \
  ca.unb.lib.generator="drupal11"
