#!/bin/bash

TMPDIR=$( mktemp -d || exit )

convert ${1} -crop ${2} ${TMPDIR}/%d.pnm >&2

echo ".tile { background-size: 100% 100%; color: transparent !important; }"

pushd ${TMPDIR} >&2
for tile in *.pnm; do
    echo -n ".tile_${tile%%.pnm} { background-image: url(data:image/png;base64,"
    pnmtopng -compression 9 ${tile} | base64 -w0
    echo "); }"
done
popd >&2

rm -r $TMPDIR >&2
