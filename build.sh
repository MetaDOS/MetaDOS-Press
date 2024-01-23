# sever product
IMAGE=press_metados
TAG=0.0.5

docker build --platform=linux/amd64 -t $IMAGE:$TAG -t $IMAGE\:latest --target $IMAGE -f Dockerfile .

docker tag $IMAGE:$TAG ghcr.io/ecraftagency/$IMAGE:$TAG
docker push ghcr.io/ecraftagency/$IMAGE:$TAG
docker tag $IMAGE\:latest ghcr.io/ecraftagency/$IMAGE\:latest
docker push ghcr.io/ecraftagency/$IMAGE\:latest