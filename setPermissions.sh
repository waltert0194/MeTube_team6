find . \
    \( -type f -exec chmod 644 {} \; \) , \
    \( -type d -exec chmod 755 {} \; \)
