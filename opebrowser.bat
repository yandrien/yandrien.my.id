@echo off
set BROWSER=chrome
set URL=http://0.0.0.0:8080/latihanweb/

if "%BROWSER%"=="google" (
  start google-chrome --incognito --start-fullscreen %URL% || start %URL%
) else if "%BROWSER%"=="chrome" (
  start chrome --incognito --start-fullscreen %URL% || start %URL%
) else if "%BROWSER%"=="edge" (
  start microsoft-edge:%URL% --inprivate --fullscreen || start %URL%
) else if "%BROWSER%"=="firefox" (
  start firefox -private-window -fullscreen %URL% || start %URL%
) else if "%BROWSER%"=="safari" (
  start safari -private %URL% || start %URL%
) else if "%BROWSER%"=="opera" (
  start opera --private --fullscreen %URL% || start %URL%
) else if "%BROWSER%"=="ucbrowser" (
  start ucbrowser -incognito %URL% || start %URL%
) else if "%BROWSER%"=="vivaldi" (
  start vivaldi --incognito --fullscreen %URL% || start %URL%
) else if "%BROWSER%"=="samsunginternet" (
  start samsunginternet --incognito %URL% || start %URL%
) else if "%BROWSER%"=="brave" (
  start brave --incognito --start-fullscreen %URL% || start %URL%
) else if "%BROWSER%"=="duckduckgo" (
  :: DuckDuckGo tidak memiliki browser desktop, sehingga akan membuka URL di browser default
  start %URL%
) else if "%BROWSER%"=="yandex" (
  start yandexbrowser --incognito --fullscreen %URL% || start %URL%
) else (
  start %URL%
)
