[![cd](https://github.com/james-ransom/eks-gha-auto-deploy-fortune/actions/workflows/cd.yml/badge.svg)](https://github.com/james-ransom/eks-gha-auto-deploy-fortune/actions/workflows/cd.yml)

# A simple! CI/CD

Commit code -> Github Actions -> Run tests -> push to Amazon EKS [Managed Kubernetes Service – Amazon EKS]


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




[force push v3]
