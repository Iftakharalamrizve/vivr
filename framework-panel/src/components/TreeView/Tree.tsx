import { Key } from "react";
import TreeNode from "./TreeNode";
import { IfosTreeModel } from "./../../models/ifostree-model";

type TreeDataStructure = {
  treeData?: Array<IfosTreeModel>;
  nodeLength?: Key;
};

function Tree(props: TreeDataStructure) {
  const { treeData, nodeLength } = props;
  return (
    <ul id="tree" className="tree">
      {treeData ? (
        <>
          {treeData.map((node: IfosTreeModel, index: number) => (
            <TreeNode
              node={node}
              key={node._id}
              lastNode={index === nodeLength ? true : false}
            />
          ))}
        </>
      ) : null}
    </ul>
  );
}
export default Tree;
