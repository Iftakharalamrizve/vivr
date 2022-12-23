import React, { useState } from "react";
import Tree from "./Tree";
import { IfosTreeModel } from "./../../models/ifostree-model";

type TreeNodeStructure = {
  node: IfosTreeModel;
  lastNode: boolean;
};

function TreeNode(props: TreeNodeStructure) {
  const { node, lastNode } = props;
  const { children, label } = node;
  const [showChildren, setShowChildren] = useState(false);
  const handleClick = () => {
    setShowChildren(!showChildren);
  };
  return (
    <>
      <li
        className={lastNode ? "last" : ""}
        onClick={children ? handleClick : ()=>{}}
      >
        <img
          alt=""
          id={showChildren ? "toggle_off" : "toggle_on"}
          className={
            children ? "exp_col commmon-showable" : "exp_col commmon-visable"
          }
          src={
            showChildren
              ? "/assets/tree/collapse.png"
              : "./assets/tree/expand.png"
          }
        />
        <span className="node">
          <img
            alt=""
            className="icon_tree"
            src={children ? "/assets/tree/folder.png" : "/assets/tree/file.png"}
          />
          <span>{label}</span>
        </span>
      </li>

      {showChildren && (
        <li>
          <Tree
            treeData={children}
            nodeLength={children ? children.length - 1 : undefined}
          />
        </li>
      )}
    </>
  );
}

export default TreeNode;
