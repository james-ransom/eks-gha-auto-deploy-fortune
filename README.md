[![cd](https://github.com/james-ransom/eks-gha-auto-deploy-fortune/actions/workflows/cd.yml/badge.svg)](https://github.com/james-ransom/eks-gha-auto-deploy-fortune/actions/workflows/cd.yml)

# A simple! CI/CD 🚀

Commit code -> Github Actions -> Run tests -> push Amazon EKS 🔥🔥

Demo: http://fortune.dance/

# This is an example of: 

1) Github actions building the image after any commit and push

2) auto pushing to a eks cluster, with autoscaling enabled 

# Let's GO! 

You need these in your Github actions secrets: <br>
<img src='https://raw.githubusercontent.com/james-ransom/eks-gha-auto-deploy-fortune/main/images/keysyouneed.png' width='700px'>

# To get KUBECONFIG

```
aws eks --region us-east-1 update-kubeconfig --name fortune
mv ~/.kube/config  ~/.kube/bk_config
cat ~/.kube/config 
mv ~/.kube/bk_config  ~/.kube/config
```

# Setup your EKS cluster 
```
eksctl create cluster --name fortune --region us-east-1
```

# Setup your nodegroup 
```
eksctl create nodegroup \
  --cluster fortune \
  --region us-east-1 \
  --name fortune-node \
  --node-type m5.small \
  --nodes 1 \
  --nodes-min 1 \
  --nodes-max 4 
```

# Setup your container registry and get your GCR_REPRO_URL
```
$ aws ecr create-repository --region us-east-1 --repository-name fortune
{
    "repository": {
        "repositoryArn": "arn:aws:ecr:us-east-1:XXXXXXXX:repository/fortune",
        "registryId": "XXXXXXXX",
        "repositoryName": "fortune",
        "repositoryUri": "XXXXXXXX.dkr.ecr.us-east-1.amazonaws.com/fortune",
        "createdAt": "2022-10-14T07:29:31-07:00",
        "imageTagMutability": "MUTABLE",
        "imageScanningConfiguration": {
            "scanOnPush": false
        },
        "encryptionConfiguration": {
            "encryptionType": "AES256"
        }
    }
}
```
In the above case your GCR_REPRO_URL is XXXXXXXX.dkr.ecr.us-east-1.amazonaws.com


# Let's BUILD! Any commit will trigger a build and push! 
<img src='https://raw.githubusercontent.com/james-ransom/eks-gha-auto-deploy-fortune/main/images/build.png' width='400px'>

# How can we see our pods/nodes/endpoints
```
aws eks --region us-east-1 update-kubeconfig --name fortune

$ kubectl get pods
NAME                           READY   STATUS             RESTARTS   AGE
backend-k8s-5b4c97b57b-7mkxr   0/1     Pending            0          33s
backend-k8s-5b4c97b57b-gbzq7   0/1     Pending            0          33s
backend-k8s-5b4c97b57b-km4qn   0/1     Pending            0          33s

```

```
$ kubectl get services
NAME          TYPE           CLUSTER-IP       EXTERNAL-IP                                                               PORT(S)        AGE
backend-k8s   LoadBalancer   10.100.253.226   XXXXXXX.us-east-1.elb.amazonaws.com   80:32270/TCP   15h
kubernetes    ClusterIP      10.100.0.1       <none>                                                                    443/TCP        43d
$ 
```

```
$ eksctl get nodegroup --cluster=fortune 

CLUSTER	NODEGROUP		STATUS	CREATED			MIN SIZE	MAX SIZE	DESIRED CAPACITY	INSTANCE TYPE	IMAGE ID	ASG NAME						TYPE
fortune	small-group-scaling	ACTIVE	2022-11-26T01:54:06Z	1		10		6			t2.small	AL2_x86_64	eks-small-group-scaling-XX	managed
```


# I want to scale! 🚀🚀🚀

```
kubect apply -f hpa.yaml #horizontal pod scaler 
```

```
kubect apply -f cluster-autoscaler-autodiscover.yaml #nodeautoscaler
```

# I want to see the scaling work! 🚀🚀🚀
```
$ kubectl get hpa
NAME          REFERENCE                TARGETS   MINPODS   MAXPODS   REPLICAS   AGE
backend-k8s   Deployment/backend-k8s   0%/60%    1         10000     2          2d2h
```

```
kubectl -n kubectlube-system logs -f deployment.apps/cluster-autoscaler #note! you will have to update the cluster name from fortune, if you change it
```

[force push v3]
